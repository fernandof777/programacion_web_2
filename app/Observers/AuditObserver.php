<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->record('created', $model, ['new' => $this->safe($model->getAttributes())]);
    }

    public function updated(Model $model): void
    {
        $this->record('updated', $model, [
            'old' => $this->safe(array_intersect_key($model->getOriginal(), $model->getChanges())),
            'new' => $this->safe($model->getChanges()),
        ]);
    }

    public function deleted(Model $model): void
    {
        $this->record('deleted', $model, ['old' => $this->safe($model->getOriginal())]);
    }

    private function record(string $event, Model $model, array $changes): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => $model::class,
            'auditable_id' => $model->getKey(),
            'changes' => $changes,
            'ip_address' => request()?->ip(),
            'user_agent' => mb_substr((string) request()?->userAgent(), 0, 500),
        ]);
    }

    private function safe(array $values): array
    {
        return collect($values)->except(['password', 'remember_token'])->all();
    }
}
