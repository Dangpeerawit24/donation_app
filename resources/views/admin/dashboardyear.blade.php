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

        <!-- กราฟ -->
        <div class="flex justify-center">
            <div class="w-full md:w-3/4 h-96 md:min-h-96 bg-white p-6 rounded-lg shadow-lg" style="height: 400px;">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>

        <!-- ตารางข้อมูล -->
        <div class="mt-10 flex justify-center">
            <div class="w-full md:w-3/4 bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-center mb-4">รายละเอียดข้อมูลกองบุญ</h2>
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
                                <td colspan="4" class="border px-4 py-2 font-bold text-right">ยอดรวมทั้งหมด</td>
                                <td class="border px-4 py-2 font-bold">{{ number_format($totalAmount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script>
        const ctx = document.getElementById('yearlyChart').getContext('2d');
        const yearlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels), // ชื่อกองบุญ
                datasets: [{
                    label: 'ยอดรวมกองบุญ (บาท)',
                    data: @json($data), // ยอดรวม
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'ยอดรวมกองบุญประจำปี {{ $year }}'
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(value, index, values) {
                                // ตัดข้อความให้เหลือแค่ 10 ตัวอักษร
                                return this.getLabelForValue(value).length > 10 ?
                                    this.getLabelForValue(value).substring(0, 10) + '...' :
                                    this.getLabelForValue(value);
                            },
                            maxRotation: 0, // ป้องกันการหมุนข้อความ
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }

        });
    </script>
@endsection
