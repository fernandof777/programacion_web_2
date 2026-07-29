<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'evento' => ['nullable', 'in:created,updated,deleted'],
            'buscar' => ['nullable', 'string', 'max:100'],
        ]);

        $registros = AuditLog::with('user')
            ->when($request->evento, fn ($query, $event) => $query->where('event', $event))
            ->when($request->buscar, fn ($query, $value) => $query->where('auditable_type', 'like', "%{$value}%"))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('auditoria.index', compact('registros'));
    }
}
