<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ระบบกองบุญออนไลน์</title>
<link rel="icon" type="" href="{{ asset('img/AdminLogo.png') }}" />
<script src="{{ asset('style.js') }}"></script>
<link rel="stylesheet" href="{{ asset('all.min.css') }}">
<link rel="stylesheet" href="{{ asset('sweetalert2.min.css') }}">
<script src="{{ asset('sweetalert2@11.js') }}"></script>
<script src="{{ asset('xlsx.full.min.js') }}"></script>
<script src="{{ asset('jquery-3.7.1.js') }}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    h1.line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .scrollbar-hide {
        /* ซ่อน Scrollbar */
        -ms-overflow-style: none;
        /* สำหรับ IE และ Edge */
        scrollbar-width: none;
        /* สำหรับ Firefox */
        overflow-y: scroll;
        /* เพื่อให้สามารถเลื่อนดูเนื้อหาได้ */
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
        /* สำหรับ Chrome, Safari และ Opera */
    }

    .scrollbar-custom {
        scrollbar-width: thin;
        /* สำหรับ Firefox */
        scrollbar-color: #888 #f5f5f5;
        /* สีของ Scrollbar */
    }

    .scrollbar-custom::-webkit-scrollbar {
        width: 8px;
        /* ความกว้างของ Scrollbar */
    }

    .scrollbar-custom::-webkit-scrollbar-track {
        background: #f5f5f5;
        /* สีพื้นหลังของ Scrollbar */
    }

    .scrollbar-custom::-webkit-scrollbar-thumb {
        background-color: #888;
        /* สี Scrollbar */
        border-radius: 8px;
        /* มุมมน */
        border: 2px solid #f5f5f5;
        /* ขอบ Scrollbar */
    }
</style>
