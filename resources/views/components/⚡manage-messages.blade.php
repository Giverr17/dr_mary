<?php

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReplyToMessage;

new class extends Component
{
    public $messages;
    public $viewingId = null;
    public $replyBody = '';
    public $replySubject = '';
    public $statusMessage = null;

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = Message::latest()->get();
    }

    public function toggleRead($id)
    {
        $message = Message::find($id);
        $message->update([
            'read_at' => $message->read_at ? null : now(),
        ]);
        $this->loadMessages();
    }

    public function delete($id)
    {
        Message::find($id)->delete();
        $this->loadMessages();
    }

    public function openMessage($id)
    {
        $this->viewingId = $this->viewingId === $id ? null : $id;
        $this->statusMessage = null;
        if ($this->viewingId) {
            $message = Message::find($id);
            if (!$message->read_at) {
                $message->update(['read_at' => now()]);
            }
            $this->replySubject = 'Re: ' . ($message->subject ?? '');
            $this->replyBody = "\n\n---\nOriginal message:\n" . ($message->body ?? '');
        }
        $this->loadMessages();
    }

    public function sendReply($id)
    {
        $this->validateReply();
        $message = Message::find($id);
        if (!$message || !$message->email) {
            $this->statusMessage = 'Recipient not found.';
            return;
        }
        try {
            Mail::to($message->email)->queue(new ReplyToMessage($this->replySubject, $this->replyBody));
            $message->update(['replied_at' => now()]);
            $this->statusMessage = 'Reply sent successfully. It has been queued for delivery.';
            $this->replyBody = '';
        } catch (\Exception $e) {
            $this->statusMessage = 'Failed to send reply: ' . $e->getMessage();
        }
        $this->loadMessages();
    }

    public function cancelReply()
    {
        $this->replyBody = '';
        $this->statusMessage = null;
    }

    protected function validateReply()
    {
        if (!trim($this->replyBody)) {
            throw new \Exception('Reply body cannot be empty.');
        }
    }
};
?>

<div>
    <div class="mb-8">
        <h2 class="text-2xl font-display font-bold text-navy">Inquiry Inbox</h2>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($messages as $message)
            <div class="group {{ $message->read_at ? 'bg-white' : 'bg-primary/5' }} transition-colors">
                <div class="px-6 py-4 flex items-center justify-between gap-4 cursor-pointer" wire:click="openMessage({{ $message->id }})">
                    <div class="flex items-center gap-4 flex-1">
                        <div class="w-2 h-2 rounded-full {{ $message->read_at ? 'bg-transparent' : 'bg-primary' }}"></div>
                        <div class="min-w-0">
                            <span class="block font-bold text-navy truncate">{{ $message->name }}</span>
                            <span class="block text-[10px] text-slate-400 uppercase tracking-widest">{{ $message->organization ?: 'Personal' }}</span>
                        </div>
                    </div>
                    <div class="flex-1 hidden md:block">
                        <span class="text-sm font-medium text-slate-700">{{ $message->subject }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        @if($message->replied_at)
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-widest rounded-md whitespace-nowrap">Replied</span>
                        @endif
                        <span class="text-xs text-slate-400 whitespace-nowrap">{{ $message->created_at->diffForHumans() }}</span>
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click.stop="toggleRead({{ $message->id }})" class="p-2 text-slate-400 hover:text-primary" title="{{ $message->read_at ? 'Mark as Unread' : 'Mark as Read' }}">
                                <span class="material-symbols-outlined text-lg">{{ $message->read_at ? 'mark_as_unread' : 'done_all' }}</span>
                            </button>
                            <button wire:confirm="Delete this message?" wire:click.stop="delete({{ $message->id }})" class="p-2 text-slate-400 hover:text-red-500">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                @if($viewingId === $message->id)
                <div class="px-6 pb-6 pt-2 bg-slate-50 border-t border-slate-100 animate-in slide-in-from-top-2 duration-300">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-xl overflow-hidden">
                        <!-- Message Header -->
                        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h3 class="text-lg font-display font-bold text-navy">{{ $message->subject }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">From:</span>
                                    <span class="text-sm font-bold text-navy">{{ $message->name }}</span>
                                    <span class="text-sm text-slate-500">&lt;{{ $message->email }}&gt;</span>
                                </div>
                            </div>
                            <div class="text-left md:text-right">
                                <span class="text-xs text-slate-400 block">{{ $message->created_at->format('F d, Y @ H:i') }}</span>
                                <span class="text-[10px] text-slate-400 uppercase tracking-widest mt-1 block">{{ $message->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Message Content -->
                        <div class="px-8 py-8">
                            <div class="prose prose-slate max-w-none">
                                <div class="text-slate-700 text-base leading-relaxed whitespace-pre-wrap">{{ $message->body }}</div>
                            </div>
                        </div>

                        <!-- Reply Section -->
                        <div class="px-8 py-8 bg-slate-50 border-t border-slate-200">
                            @if($message->replied_at)
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                    </div>
                                    <h4 class="font-bold text-green-700">Replied on {{ $message->replied_at->format('M d, Y g:i A') }}</h4>
                                </div>
                                <p class="text-sm text-slate-500">This message has already been replied to. No further action is required.</p>
                            @else
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-8 h-8 rounded-full bg-navy text-primary flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm">reply</span>
                                    </div>
                                    <h4 class="font-bold text-navy">Compose Reply</h4>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Subject</label>
                                        <input wire:model.defer="replySubject" 
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" 
                                            placeholder="Subject" />
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Message</label>
                                        <textarea wire:model.defer="replyBody" 
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm min-h-[200px] focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none resize-none" 
                                            placeholder="Write your professional response..."></textarea>
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                                        <div class="flex items-center gap-3">
                                            <button wire:click.prevent="sendReply({{ $message->id }})" 
                                                wire:loading.attr="disabled"
                                                class="px-8 py-3 bg-navy text-primary font-bold rounded-xl hover:bg-navy/90 transition-all shadow-lg flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                                <span wire:loading.remove wire:target="sendReply">Send Reply</span>
                                                <span wire:loading wire:target="sendReply" class="animate-spin material-symbols-outlined text-sm">progress_activity</span>
                                                <span wire:loading wire:target="sendReply">Sending...</span>
                                                <span wire:loading.remove wire:target="sendReply" class="material-symbols-outlined text-sm">send</span>
                                            </button>
                                            <button wire:click="cancelReply" class="px-6 py-3 text-slate-500 font-bold hover:text-navy transition-colors text-sm">
                                                Cancel
                                            </button>
                                        </div>

                                        @if($statusMessage)
                                        <div class="flex items-center gap-2 px-4 py-2 rounded-lg {{ str_contains($statusMessage, 'success') ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} animate-in fade-in duration-300">
                                            <span class="material-symbols-outlined text-sm">{{ str_contains($statusMessage, 'success') ? 'check_circle' : 'error' }}</span>
                                            <span class="text-xs font-bold">{{ $statusMessage }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="px-6 py-12 text-center text-slate-400">
                <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                <p>Your inbox is empty.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>