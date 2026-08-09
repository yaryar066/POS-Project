<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Customer Reviews & Feedback - Finexy POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #E2E8F0; }</style>
</head>
<body class="p-4 sm:p-6 lg:p-8 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-[1200px] bg-white rounded-[32px] shadow-2xl p-6 lg:p-8 border border-gray-100 space-y-6">
        
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-[#F05423]">← Back to Dashboard</a>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mt-1">Customer Reviews & Feedbacks</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-amber-50 border border-amber-100 px-4 py-2 rounded-2xl text-xs">
                    <span class="text-gray-500 font-semibold">Average Rating:</span>
                    <span class="font-extrabold text-amber-500 ml-1">★ {{ $avgRating }} / 5.0</span>
                </div>
            </div>
        </div>

        <div class="bg-[#F6F7F9] p-6 rounded-[28px] overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="text-gray-400 text-[11px] border-b border-gray-200 pb-2">
                    <tr>
                        <th class="pb-3">Order No</th>
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">Rating</th>
                        <th class="pb-3">Comment / Feedback</th>
                        <th class="pb-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60 font-medium">
                    @forelse($reviews as $review)
                        <tr>
                            <td class="py-3 font-bold text-gray-900">{{ $review->order->order_number ?? 'N/A' }}</td>
                            <td class="py-3 font-semibold text-gray-700">{{ $review->customer->name ?? 'Guest Customer' }}</td>
                            <td class="py-3 font-extrabold text-amber-500">
                                {{ str_repeat('★', $review->rating) }} ({{ $review->rating }}/5)
                            </td>
                            <td class="py-3 text-gray-600">{{ $review->comment ?? 'No comment provided.' }}</td>
                            <td class="py-3 text-gray-400">{{ $review->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-6 text-gray-400">No reviews recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $reviews->links() }}</div>

    </div>
</body>
</html>