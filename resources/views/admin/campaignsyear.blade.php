@extends('layouts.main')
@php
    $manu = 'แดชบอร์ด';
    $year = now()->year + 543; // แปลงปีเป็น พ.ศ.
@endphp

@section('content')
    <div class="flex flex-col">
        <!-- Header -->
        <div class="text-center my-6">
            <h1 class="text-3xl font-bold text-green-600">
                ข้อมูลกองบุญประจำปี {{ $year }}
            </h1>
        </div>
        <!-- ตารางข้อมูล -->
        <div class="mt-4 flex justify-center">
            <div class="w-full md:w-5/5 bg-white rounded-lg shadow-lg p-6">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full text-center border-collapse border">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-4 py-2">#</th>
                                <th class="px-4 text-nowrap py-2">ชื่อกองบุญ</th>
                                <th class="px-4 text-nowrap py-2">ยอดรวมกองบุญ</th>
                                <th class="px-4 text-nowrap py-2">ราคาต่อหน่วย</th>
                                <th class="px-4 text-nowrap py-2">ยอดรวม (บาท)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $index => $result)
                                <tr>
                                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="border text-nowrap px-4 py-2">{{ $result->name }}</td>
                                    <td class="border text-nowrap px-4 py-2">{{ number_format($result->total_value, 0) }}
                                    </td>
                                    <td class="border text-nowrap px-4 py-2">{{ number_format($result->price, 2) }}</td>
                                    <td class="border text-nowrap px-4 py-2">{{ number_format($result->total_amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <!-- แถวสรุปยอดรวม -->
                        <tfoot>
                            <tr class="bg-gray-100">
                                <td colspan="2" class="border px-4 py-2 font-bold text-right">ยอดรวม Value ทั้งหมด</td>
                                <td class="border px-4 py-2 font-bold">{{ number_format($totalValue, 0) }}</td>
                                <td class="border px-4 py-2 font-bold text-right">ยอดรวมทั้งหมด</td>
                                <td class="border px-4 py-2 font-bold">{{ number_format($totalAmount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
