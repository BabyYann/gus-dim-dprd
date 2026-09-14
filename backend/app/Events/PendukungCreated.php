<?php

namespace App\Events;

use App\Models\Pendukung;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendukungCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Pendukung $pendukung)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('gusdim-updates'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'PendukungCreated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->pendukung->id,
            'nama' => $this->pendukung->nama,
            'jalur' => $this->pendukung->jalur,
            'kecamatan' => $this->pendukung->kecamatan,
            'desa' => $this->pendukung->desa,
            'input_by_user_name' => $this->pendukung->input_by_user_name,
            'created_at' => $this->pendukung->created_at?->toIso8601String() ?? now()->toIso8601String(),
        ];
    }
}
