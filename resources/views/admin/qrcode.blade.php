@extends('layouts.main')
@php
    $manu = 'qrcode';
@endphp
@section('content')
    <style>
        /* สไตล์สำหรับฟอร์ม */
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-container input[type="url"],
        .form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #5c67f2;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #3e4cde;
        }

        .qr-code-container {
            text-align: center;
            justify-items: center;
            margin-top: 20px;
        }

        .qr-code-container h3 {
            font-size: 1.2em;
            color: #333;
        }

        .qr-code-container img {
            margin-top: 10px;
            max-width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
    </style>

    <div class="form-container">
        <form action="{{ route('qr-code.generate') }}" method="POST">
            @csrf
            <label for="url">URL:</label>
            <input type="url" name="url" id="url" required>

            <label for="filename">Filename:</label>
            <input type="text" name="filename" id="filename" required>

            <button type="submit">Generate QR Code</button>
        </form>

        @if (isset($qrCodePath))
            <div class="qr-code-container">
                <h3>Your QR Code:</h3>
                <img src="{{ asset($qrCodePath) }}" alt="QR Code">
            </div>
        @endif
    </div>
@endsection
