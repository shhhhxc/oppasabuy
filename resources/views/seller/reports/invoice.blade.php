<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} - {{ $store->store_name ?? 'Oppasabuy' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            -webkit-print-color-adjust: exact;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white; padding: 0; }
            .bg-gray-100 { background: white; }
            .print-shadow-none { shadow: none !important; border: 1px solid #eee; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="p-8">
        {{-- Print Action Bar --}}
        <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center no-print">
            <a href="{{ route('seller.invoices.index') }}" class="text-gray-500 font-bold text-sm no-underline hover:text-gray-800">
                <i class="bi bi-arrow-left"></i> Back to Generator
            </a>
            <button onclick="window.print()" class="bg-[#0d47a1] text-white px-6 py-2 rounded-lg font-bold shadow-lg hover:bg-blue-800 transition">
                <i class="bi bi-printer-fill mr-2"></i> Print Invoice
            </button>
        </div>

        {{-- Invoice Sheet --}}
        <div class="max-w-4xl mx-auto bg-white shadow-2xl p-12 rounded-sm border-t-8 border-[#0d47a1] print:shadow-none print:p-0 print:border-t-0">
            
            <div class="flex justify-between items-start mb-12">
                <div>
                    <h1 class="text-5xl font-black text-[#0d47a1] italic tracking-tighter uppercase">Sales Invoice</h1>
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px] mt-2">Transaction Receipt</p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-black text-gray-800 uppercase tracking-tighter italic">Oppasabuy</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $store->store_name ?? 'Authorized Seller' }}</p>
                    <p class="text-[10px] text-gray-400">Seller ID: #{{ auth()->id() }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-12 mb-12">
                <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100">
                    <h3 class="font-black uppercase text-[#0d47a1] text-[10px] tracking-[0.2em] mb-3">Sold To:</h3>
                    <p class="font-black text-gray-800 text-xl tracking-tight">{{ $order->buyer->name }}</p>
                    <p class="text-gray-500 font-medium text-sm">{{ $order->buyer->email }}</p>
                </div>
                <div class="p-6 text-right">
                    <div class="mb-4">
                        <p class="text-gray-400 uppercase font-black text-[10px] tracking-widest">Invoice No:</p>
                        <p class="text-2xl font-black text-gray-800">#{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 uppercase font-black text-[10px] tracking-widest">Date Issued:</p>
                        <p class="font-bold text-gray-800">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <table class="w-full mb-12">
                <thead>
                    <tr class="bg-[#0d47a1] text-white text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-6 py-4 text-left rounded-l-xl">Item Description</th>
                        <th class="px-4 py-4 text-center">Qty</th>
                        <th class="px-4 py-4 text-right">Unit Price</th>
                        <th class="px-6 py-4 text-right rounded-r-xl">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-6 py-6 font-bold text-gray-800">{{ $item->product->name }}</td>
                        <td class="px-4 py-6 text-center font-bold text-gray-500">{{ $item->quantity }}</td>
                        <td class="px-4 py-6 text-right font-bold text-gray-500">₱{{ number_format($item->price, 2) }}</td>
                        <td class="px-6 py-6 text-right font-black text-gray-900">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-end">
                <div class="w-80 space-y-3">
                    <div class="flex justify-between text-gray-400 font-black text-[10px] uppercase tracking-widest">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($order->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-400 font-black text-[10px] uppercase tracking-widest">
                        <span>VAT (0%)</span>
                        <span>₱0.00</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t-2 border-gray-100">
                        <div class="leading-none">
                            <span class="font-black text-[#0d47a1] uppercase italic tracking-tighter text-lg">Total Amount</span>
                            <p class="text-[8px] text-gray-400 uppercase font-bold tracking-widest">Philippine Peso</p>
                        </div>
                        <span class="text-3xl font-black text-[#0d47a1]">₱{{ number_format($order->total_price, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-24 border-t border-dashed border-gray-200 pt-8 text-center">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em]">Thank you for your business!</p>
                <p class="text-[9px] text-gray-300 mt-2 italic">This is a system-generated receipt for Oppasabuy Marketplace.</p>
            </div>
        </div>
    </div>

</body>
</html>