<x-manage-layout>
    <x-slot name="header">Overview</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Stat Cards -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined">mail</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Unread Messages</h3>
                    <p class="text-2xl font-display font-bold text-navy">{{ $messageCount }}</p>
                </div>
            </div>
            <a href="/manage/messages" class="text-xs font-bold text-primary hover:underline">View All Messages →</a>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-xl bg-navy/10 text-navy flex items-center justify-center">
                    <span class="material-symbols-outlined">event</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Upcoming Events</h3>
                    <p class="text-2xl font-display font-bold text-navy">{{ $eventCount }}</p>
                </div>
            </div>
            <a href="/manage/events" class="text-xs font-bold text-primary hover:underline">Manage Events →</a>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 text-green-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">visibility</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Site Status</h3>
                    <p class="text-2xl font-display font-bold text-navy">Live</p>
                </div>
            </div>
            <a href="/" target="_blank" class="text-xs font-bold text-primary hover:underline">Visit Public Site →</a>
        </div>
    </div>

    <!-- Recent Messages Table Preview -->
    <div class="mt-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-display font-bold text-navy">Recent Inquiries</h2>
            <a href="/manage/messages" class="text-sm font-bold text-primary hover:underline">View All</a>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">From</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Subject</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentMessages as $message)
                    <tr class="{{ $message->read_at ? 'opacity-60' : 'bg-primary/5' }}">
                        <td class="px-6 py-4">
                            <span class="block font-bold text-navy">{{ $message->name }}</span>
                            <span class="block text-xs text-slate-500">{{ $message->email }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-700">{{ $message->subject?->label() }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-500">{{ $message->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="/manage/messages" class="text-xs font-bold text-primary hover:underline">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">No messages yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-manage-layout>
