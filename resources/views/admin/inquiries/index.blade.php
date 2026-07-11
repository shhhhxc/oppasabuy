@extends('layouts.admin') {{-- Assuming your provided code is a layout --}}

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-black text-gray-800 mb-6 uppercase tracking-tight">Incoming Inquiries</h2>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-400 text-[10px] uppercase tracking-widest border-b">
                    <th class="pb-4">Buyer</th>
                    <th class="pb-4">Product Name</th>
                    <th class="pb-4">Type</th>
                    <th class="pb-4">Qty</th>
                    <th class="pb-4">Status</th>
                    <th class="pb-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inquiries as $inquiry)
                <tr class="border-b last:border-0 hover:bg-gray-50">
                    <td class="py-4 font-semibold text-gray-700">{{ $inquiry->buyer->name }}</td>
                    <td class="py-4 text-gray-600">{{ $inquiry->product_name }}</td>
                    <td class="py-4 text-gray-600">{{ $inquiry->product_type }}</td>
                    <td class="py-4 text-gray-600">{{ $inquiry->quantity }}</td>
                    <td class="py-4">
                        <span class="px-2 py-1 bg-yellow-50 text-yellow-600 rounded-full text-[10px] font-bold uppercase">{{ $inquiry->status }}</span>
                    </td>
                    <td class="py-4">
                        <a href="{{ route('chat.show', $inquiry->id) }}" class="text-[#A31D1D] font-bold text-sm hover:underline">View Chat</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection