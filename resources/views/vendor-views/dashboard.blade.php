@extends('vendor-views.layouts.dashboard-main')
@push('css')

    <link rel="stylesheet" href="{{ asset('assets/vendor/flatpickr/dist/flatpickr.min.css') }}">
    <meta name="latest_orders" content="{{ json_encode($latest_orders) }}">
    <style>
        . .card-slie-arrow {
            left: 35px;
            right: 0px;
            width: 35px;
            height: 35px;
            position: absolute;
            top: 35px;
            -o-object-fit: cover;
            object-fit: cover;
        }

        . {
            text-align: center;
            font-size: 18px;
            background: #fff;
        }

        .swiper . {
            height: 300px;
            line-height: 300px;
        }

        .form-control:focus {
            border: 0px !important;
            box-shadow: none !important;
        }

        .icon-widget {
            border: 3px dashed var(--bs-primary);
            border-radius: 50%;
            padding: 5px;
            background-color: #fff;
        }

        .nav-tabs .nav-link span {
            border: 1px solid blue;
            display: inline-block;
            /* Ensure span is visible */
            padding: 2px;
            /* Add padding for better visibility */
            border-radius: 7px;
        }

        .nav-tabs .nav-link.active span {
            border: 1px solid white;
            border-radius: 7px;
        }

        @media screen and (max-width: 767px) {
            .icon-widget {
                padding: 5px;
            }

            .icon-widget svg {
                height: 35px;
                width: 35px;
            }
        }
    </style>
    <style>
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .tab {
            padding: 6px 20px;
            border-radius: 25px;
            border: 1px solid #007bff;
            background-color: #e7f3ff;
            color: #007bff;
            font-weight: 500;
            cursor: pointer;
            font-size: 14px;
        }

        .tab .badge {
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 7px;
            margin-left: 6px;
            font-size: 12px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 20px;
            margin-bottom: 25px;

        }

        .card-content {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            gap: 0;
            /* No gap between sections */
            position: relative;
        }

        .left,
        .middle,
        .right {
            flex: 1 1 30%;
            display: flex;
            flex-direction: column;
            padding: 0px 15px;
        }

        .vertical-divider {
            border-left: 2px dashed #dcdcdc;
        }


        .delivery-tag {
            font-size: 12px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 4px;
            width: fit-content;
        }

        .zomato {
            background-color: #f3eaff;
            color: #6f42c1;
        }

        .self {
            background-color: #e0f8ec;
            color: #218838;
        }

        .paid,
        .cash {
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }

        .paid {
            background: #e3f0ff;
            color: #0056b3;
        }

        .cash {
            background: #ffe3e3;
            color: #b30000;
        }

        .order-btn {
            width: 100%;
            margin: 8px 0;
            padding: 10px 16px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, var(--bs-primary) 0%, #e0e0e0 0%);
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            cursor: default;
            position: relative;
            transition: background 0.5s ease;
        }



        .user-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .track-call a {
            font-size: 13px;
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .progress-bar-wrap {
            width: 100%;
            height: 6px;
            background: #ddd;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: #28a745;
            width: 80%;
        }

        .print-link,
        .support-link {
            color: #007bff;
            font-size: 13px;
            text-decoration: none;
            font-weight: 500;
        }

        .timeline-row {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: #555;
            margin-top: 4px;
        }

        .timeline-row .dot {
            font-size: 18px;
            line-height: 0;
            color: #888;
        }




        @media(max-width: 768px) {

            .left,
            .right {
                flex: 1 1 100%;
            }
        }

        /* Custom Delivery Man Arrival Alert Styles */
        .delivery-alert-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease-out;
        }

        .delivery-alert-modal {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            max-width: 450px;
            width: 90%;
            overflow: hidden;
            animation: slideUp 0.4s ease-out;
            position: relative;
        }

        .delivery-alert-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            padding: 20px;
            position: relative;
            color: white;
            text-align: center;
        }

        .delivery-alert-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 24px;
            animation: bounce 2s infinite;
        }

        .delivery-alert-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .delivery-alert-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .delivery-alert-content {
            padding: 25px;
            text-align: center;
        }

        .delivery-alert-title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .delivery-alert-message {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .delivery-alert-details {
            display: flex;
            justify-content: space-around;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .delivery-alert-eta,
        .delivery-alert-distance {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .delivery-alert-eta i,
        .delivery-alert-distance i {
            color: #28a745;
            font-size: 18px;
        }

        .eta-time,
        .distance-text {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        .delivery-alert-actions {
            display: flex;
            gap: 10px;
            padding: 0 25px 25px;
        }

        .btn-track,
        .btn-call {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-track {
            background: #007bff;
            color: white;
        }

        .btn-track:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-call {
            background: #28a745;
            color: white;
        }

        .btn-call:hover {
            background: #1e7e34;
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px) scale(0.9);
            }
            to { 
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        /* Mobile responsive */
        @media (max-width: 480px) {
            .delivery-alert-modal {
                width: 95%;
                margin: 20px;
            }
            
            .delivery-alert-actions {
                flex-direction: column;
            }
            
            .delivery-alert-details {
                flex-direction: column;
                gap: 15px;
            }
        }

        /* Extra Cooking Time Styles */
        .extra-cooking-time-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
        }

        .extra-cooking-input {
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .extra-cooking-input:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .update-extra-cooking {
            border-radius: 6px;
            white-space: nowrap;
        }

        .add-extra-time {
            border-radius: 6px;
            font-size: 12px;
            padding: 4px 8px;
        }

        .add-extra-time:hover {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .extra-time-display {
            font-size: 12px;
            padding: 4px 8px;
        }

        .extra-cooking-time-section .btn-group {
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .extra-cooking-time-section {
                padding: 10px;
            }
            
            .extra-cooking-time-section .d-flex.gap-2 {
                gap: 5px !important;
            }
            
            .add-extra-time {
                font-size: 11px;
                padding: 3px 6px;
            }
        }

        /* Processing Section Styles */
        .processing-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }

        .start-processing-btn {
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .start-processing-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        /* Cooking Time Modal Styles */
        .cooking-time-input-section {
            text-align: left;
        }

        .quick-time-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .quick-time-btn {
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .quick-time-btn:hover,
        .quick-time-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .btn-cancel {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-start-cooking {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #28a745;
            color: white;
        }

        .btn-start-cooking:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .btn-force-ready {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #ffc107;
            color: #212529;
        }

        .btn-force-ready:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }

        .btn-handover {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #007bff;
            color: white;
        }

        .btn-handover:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        #cookingTimeInput:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        /* Processing Time Section Styles */
        .processing-time-section {
            background: #e8f5e8;
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }

        /* Handover Section Styles */
        .handover-section {
            background: #d4edda;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            border: 1px solid #c3e6cb;
        }

        .handover-btn {
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            background-color: #007bff;
            border-color: #007bff;
        }

        .handover-btn:hover {
            background-color: #0056b3;
            border-color: #004085;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        .handover-info span {
            color: #155724 !important;
        }

        .processing-time-info {
            flex: 1;
        }

        .processing-time-info small {
            font-size: 11px;
            line-height: 1.2;
            margin-top: 2px;
        }

        @media (max-width: 768px) {
            .processing-time-section .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }
            
            .processing-time-section .order-btn {
                width: 100%;
                margin-top: 8px;
            }
        }

        /* Disabled extra cooking time section */
        .extra-cooking-time-section[style*="pointer-events: none"] {
            background: #f8f9fa !important;
            border-left-color: #6c757d !important;
        }

        .extra-cooking-time-section[style*="pointer-events: none"] input,
        .extra-cooking-time-section[style*="pointer-events: none"] button {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Force Ready Button Styles */
        .force-ready-btn {
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            white-space: nowrap;
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .force-ready-btn:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            color: #212529;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
        }

        .force-ready-btn:active {
            transform: translateY(0);
        }

        .force-ready-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 768px) {
            .force-ready-btn {
                padding: 6px 8px;
                font-size: 12px;
            }
            
            .force-ready-btn i {
                margin-right: 0;
            }
            
            .force-ready-btn .btn-text {
                display: none;
            }
        }
    </style>
@endpush
@section('content')
    <div class="conatiner-fluid content-inner">
        <div class="row">
            <div class="col-md-12 mb-3 d-none">
                <div class="bd-example">
                    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class=""
                                aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                                aria-label="Slide 2" class="active" aria-current="true"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                                aria-label="Slide 3" class=""></button>
                        </div>
                        <div class="carousel-inner rounded-5">
                            <div class="carousel-item">
                                <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#555"
                                        dy=".3em">First slide</text>
                                </svg>
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>First slide label</h5>
                                    <p>Some representative placeholder content for the first slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item active">
                                <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444"
                                        dy=".3em">Second slide</text>
                                </svg>
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Second slide label</h5>
                                    <p>Some representative placeholder content for the second slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#555"></rect><text x="50%" y="50%" fill="#333"
                                        dy=".3em">Third slide</text>
                                </svg>
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Third slide label</h5>
                                    <p>Some representative placeholder content for the third slide.</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3 d-none">
                <div class="bd-example">
                    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class=""
                                aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                                aria-label="Slide 2" class="active" aria-current="true"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                                aria-label="Slide 3" class=""></button>
                        </div>
                        <div class="carousel-inner rounded-5">
                            <div class="carousel-item">
                                <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#555"
                                        dy=".3em">First slide</text>
                                </svg>
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>First slide label</h5>
                                    <p>Some representative placeholder content for the first slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item active">
                                <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444"
                                        dy=".3em">Second slide</text>
                                </svg>
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Second slide label</h5>
                                    <p>Some representative placeholder content for the second slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <svg class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#555"></rect><text x="50%" y="50%" fill="#333"
                                        dy=".3em">Third slide</text>
                                </svg>
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Third slide label</h5>
                                    <p>Some representative placeholder content for the third slide.</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <p class="mb-0 d-flex align-items-center fw-bolder">
                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="me-2 icon-20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.56517 3C3.70108 3 3 3.71286 3 4.5904V5.52644C3 6.17647 3.24719 6.80158 3.68936 7.27177L8.5351 12.4243L8.53723 12.4211C9.47271 13.3788 9.99905 14.6734 9.99905 16.0233V20.5952C9.99905 20.9007 10.3187 21.0957 10.584 20.9516L13.3436 19.4479C13.7602 19.2204 14.0201 18.7784 14.0201 18.2984V16.0114C14.0201 14.6691 14.539 13.3799 15.466 12.4243L20.3117 7.27177C20.7528 6.80158 21 6.17647 21 5.52644V4.5904C21 3.71286 20.3 3 19.4359 3H4.56517Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                        Filter :: {{Str::ucfirst($filter)}}
                    </p>
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="dropdown me-3 fw-bolder">
                            <span class="dropdown-toggle align-items-center d-flex" id="dropdownMenuButton04" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="me-2 icon-20">
                                    <path d="M3.09277 9.40421H20.9167" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M16.442 13.3097H16.4512" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M12.0045 13.3097H12.0137" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M7.55818 13.3097H7.56744" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M16.442 17.1962H16.4512" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M12.0045 17.1962H12.0137" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M7.55818 17.1962H7.56744" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M16.0433 2V5.29078" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M7.96515 2V5.29078" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16.2383 3.5791H7.77096C4.83427 3.5791 3 5.21504 3 8.22213V17.2718C3 20.3261 4.83427 21.9999 7.77096 21.9999H16.229C19.175 21.9999 21 20.3545 21 17.3474V8.22213C21.0092 5.21504 19.1842 3.5791 16.2383 3.5791Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>

                                @if($filter == 'today')
                                    Today :
                                @elseif ($filter == 'this_week')
                                    Week:
                                @elseif ($filter == 'this_month')
                                    Month :
                                @elseif ($filter == 'this_year')
                                    Year
                                @elseif ($filter == 'previous_year')
                                    Previous Year
                                @else
                                    Select
                                @endif
                            </span>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton22"
                                style="min-width:275px;">
                                <li><a class="dropdown-item"
                                        href="{!! route('vendor.dashboard') . '?filter=this_week' !!}">This Week</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{!! route('vendor.dashboard') . '?filter=this_month' !!}">This
                                        Month</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{!! route('vendor.dashboard') . '?filter=this_year' !!}">This Year</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{!! route('vendor.dashboard') . '?filter=previous_year' !!}">Previous
                                        Year</a></li>
                                <li><a class="dropdown-item"
                                        href="{!! route('vendor.dashboard') . '?filter=today' !!}">Today</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0)">
                                        <form action="">
                                            <div class="m-0 d-flex flex-column align-items-center justify-content-center">

                                                <input type="text" name="date_range"
                                                    class="form-control range_flatpicker d-flex flatpickr-input active"
                                                    placeholder="Date Range" readonly="readonly" required>
                                                <input type="hidden" name="filter" value="custom">
                                                <button class="badge rounded-pill bg-success ms-1 mb-1 px-3 py-2"
                                                    type="submit">Go</button>
                                            </div>
                                        </form>

                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card mb-0">
                    <div
                        class="maintainance-mode-toggle-bar d-flex flex-wrap justify-content-between rounded align-items-center">

                        <h5 class="text-capitalize m-0 text--primary fw-bolder">
                            <span>
                                Temporarily Pause
                            </span>
                        </h5>
                        <label class="switch form-check form-switch ">
                            <input type="checkbox" data-temp="off" {{$restaurant->temp_close == 1 ? 'checked' : null}}
                                class="form-check-input" class="status" style="font-size: 28px;">
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-12">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item border-0">
                        <div class="card d-flex justify-content-between accordion-header mb-3" id="headingOne">
                            <button class="accordion-button bg-white rounded-4 p-0 fs-5 fw-bolder" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                aria-controls="collapseOne">
                                Overviews
                            </button>
                        </div>
                        <div id="collapseOne" class="row accordion-collapse collapse g-0" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="col-6 px-2">
                                <div class="card"
                                    style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                                                                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="progress-widget">
                                                <div class="">
                                                    <h4 class="counter text-white mb-2">{{ $count['totalOrders'] }}</h4>
                                                    <p class="text-white mb-0">
                                                        Orders
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="icon-widget">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" fill-rule="evenodd"
                                                    clip-rule="evenodd" text-rendering="geometricPrecision"
                                                    viewBox="0 0 2048 2048">
                                                    <path fill="none" d="M0 0h2048v2048H0z" />
                                                    <path fill="none" d="M255.999 255.999h1536v1536h-1536z" />
                                                    <path fill="none" d="M256 255.999h1536v1536H256z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M1783.77 501.991c-8.633-14.418-22.84-24.522-39.23-27.81l-870.058-209.76c-28.676-5.564-56.966 10.281-66.887 37.686L475.96 1144.612l110.708 40.342L900.95 383.877l755.976 192.9c-47.213 146.36-159.552 451.86-267.253 736.308-93.883 248.175-147.913 333.16-250.611 350.512-.114-.123-.227-.228-.227-.41-209.875 29.411-206.116-238.14-206.116-238.14l-670.28-270.759c-4.461 28.3-6.468 54.592-6.442 78.991.28 235.896 190.786 295.536 190.786 295.536l490.755 222.793c.105.07 76.722 36.564 159.98 32.804 224.59-5.276 303.51-168.35 402.405-429.618 142.872-377.593 287.875-800.656 289.295-805.143 5.11-16.012 3.093-33.286-5.45-47.659zm-423.72 406.944c5.11 1.394 10.403 2.139 15.626 2.139 25.846 0 49.492-17.24 56.739-43.374 8.615-31.42-9.99-63.864-41.35-72.418l-255.36-78.816c-31.006-8.58-63.819 9.85-72.39 41.244-8.545 31.402 9.877 63.847 41.367 72.444l255.369 78.781zM929.276 647.747c36.073 0 65.354 28.466 65.354 63.53 0 35.084-29.28 63.514-65.354 63.514-36.1 0-65.362-28.422-65.362-63.514 0-35.064 29.263-63.53 65.362-63.53zm-109.745 566.609c-25.24-10.08-54.25 2.27-64.311 27.484-10.132 25.355 2.393 54.285 27.633 64.363 25.241 10.08 54.102-2.33 64.224-27.685 10.07-25.215-2.305-54.092-27.546-64.162zm8.914-422.607l-18.37 45.995 45.993 18.36 12.955 207.517-54.215 42.34c-4.97 5.942-11 14.189-13.725 21.016-10.123 25.354 2.392 54.294 27.633 64.372l275.561 110.034 18.37-46.003-266.377-106.362c-2.357-.946-3.698-4.075-2.76-6.432l.894-2.235 36.23-30.771 170.033 67.896c18.265 7.292 35.758 3.584 48.212-7.424l142.269-116.274c6.432-2.76 7.37-5.117 9.22-9.737 5.45-13.663-.062-26.52-13.875-32.04L906.524 876.251l-2.244-54.224-75.835-30.28zm220.794 514.323c-25.24-10.078-54.241 2.278-64.311 27.493-10.124 25.354 2.393 54.285 27.633 64.364 25.24 10.078 54.092-2.332 64.223-27.686 10.061-25.215-2.313-54.093-27.545-64.171zm398.216-581.018c5.11 1.394 10.402 2.138 15.626 2.138 25.845 0 49.49-17.238 56.739-43.374 8.615-31.418-9.991-63.863-41.35-72.417l-255.36-78.816c-31.007-8.58-63.82 9.85-72.392 41.244-8.544 31.402 9.878 63.847 41.367 72.444l255.37 78.781zM1016.68 463.865c36.072 0 65.354 28.467 65.354 63.532 0 35.083-29.282 63.513-65.354 63.513-36.1 0-65.364-28.422-65.364-63.513 0-35.065 29.263-63.532 65.364-63.532z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 px-2">
                                <div class="card"
                                    style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                                                                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="progress-widget">
                                                <div class="">
                                                    <h4 class="counter text-white mb-2">{{ $count['sold'] }}</h4>
                                                    <p class="text-white mb-0">Delivered</p>
                                                </div>
                                            </div>
                                            <div class="icon-widget">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" viewBox="0 0 64 64">
                                                    <path fill="var(--bs-primary)"
                                                        d="M21 49h-1a10 10 0 0 1-10-10v-4a1 1 0 0 1 1-1h19a1 1 0 0 1 1 1v4a10 10 0 0 1-10 10zm-9-13v3a8 8 0 0 0 8 8h1a8 8 0 0 0 8-8v-3zm6.12-7.12a1 1 0 0 1-1-1c0-1.67 1.47-2.45 2.53-3s1.47-.81 1.47-1.24-.55-.76-1.47-1.25-2.53-1.34-2.53-3 1.47-2.44 2.53-3 1.47-.81 1.47-1.24a1 1 0 0 1 2 0c0 1.66-1.46 2.44-2.53 3-.91.48-1.47.81-1.47 1.24s.56.76 1.47 1.25c1.07.57 2.53 1.34 2.53 3s-1.46 2.44-2.53 3c-.91.49-1.47.81-1.47 1.25a1 1 0 0 1-1 .99z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M11.35 43H7.7a3.51 3.51 0 0 1-3.26-2.21A3.52 3.52 0 0 1 7.67 36h3.32a1 1 0 0 1 0 2h-3.3a1.51 1.51 0 0 0-1.25.68A1.51 1.51 0 0 0 7.7 41h3.65a1 1 0 1 1 0 2zM60 52H4a1 1 0 0 1 0-2h56a1 1 0 0 1 0 2zm-6 5H16.1a1 1 0 0 1 0-2H54a1 1 0 0 1 0 2zm-42 0h-2a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M8.27 52a1 1 0 0 1-.92-.61l-1.27-3A1 1 0 0 1 7 47h13a1 1 0 0 1 0 2H8.51l.68 1.61a1 1 0 0 1-.53 1.31 1.09 1.09 0 0 1-.39.08Z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M31.73 52a1.09 1.09 0 0 1-.39-.08 1 1 0 0 1-.53-1.31l.68-1.61H20a1 1 0 0 1 0-2h13a1 1 0 0 1 .83.45 1 1 0 0 1 .09.94l-1.27 3a1 1 0 0 1-.92.61zM58 52a1 1 0 0 1-1-1 23 23 0 0 0-37.65-17.73 1 1 0 0 1-1.28-1.54A25 25 0 0 1 59 51a1 1 0 0 1-1 1z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M47.06 35.57a1 1 0 0 1-.62-.22A20 20 0 0 0 37 31.23a1 1 0 0 1-.84-1.14 1 1 0 0 1 1.14-.84 22 22 0 0 1 10.37 4.53 1 1 0 0 1-.62 1.79zm2.8 2.68a1 1 0 0 1-.75-.34l-.47-.53A1 1 0 1 1 50.1 36c.17.19.35.38.52.58a1 1 0 0 1-.1 1.41 1 1 0 0 1-.66.26zM56 40a1 1 0 0 1-1-1V25h-2v6a1 1 0 0 1-2 0v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a1 1 0 0 1-1 1z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M54 25a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v15a1 1 0 0 1-1 1Z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M56 18h-4a2 2 0 0 1-2-2V9a1 1 0 0 1 2 0v7h4V9a1 1 0 0 1 2 0v7a2 2 0 0 1-2 2zM45 28a1 1 0 0 1-1-1v-6a1 1 0 0 1 .29-.71c2.72-2.71 1.07-8.44.23-10.77a.78.78 0 0 0-1.52.26V25a1 1 0 0 1-2 0V9.78a2.78 2.78 0 0 1 5.4-.93c.94 2.61 2.72 9-.4 12.54V27a1 1 0 0 1-1 1zm-30-3a1 1 0 0 1-1-1c0-.3-.82-.69-1.42-1-1.09-.48-2.58-1.18-2.58-2.75s1.49-2.28 2.58-2.79c.6-.28 1.42-.66 1.42-1s-.82-.68-1.42-1C11.49 15 10 14.32 10 12.75s1.49-2.27 2.58-2.78C13.18 9.69 14 9.3 14 9a1 1 0 0 1 2 0c0 1.57-1.49 2.27-2.58 2.78-.6.28-1.42.67-1.42 1s.82.68 1.42 1c1.09.51 2.58 1.21 2.58 2.78s-1.49 2.28-2.58 2.79c-.6.28-1.42.66-1.42 1s.82.69 1.42 1C14.51 21.73 16 22.43 16 24a1 1 0 0 1-1 1zm21 3h-4a1 1 0 0 1-1-1v-2a3 3 0 0 1 6 0v2a1 1 0 0 1-1 1zm-3-2h2v-1a1 1 0 0 0-2 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-6 ">
                                <div class="card"
                                    style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                                                                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="progress-widget">
                                                <div class="">
                                                    <h4 class="counter text-white mb-2">{{ $count['customers'] }}</h4>
                                                    <p class="text-white mb-0">Staff</p>
                                                </div>
                                            </div>
                                            <div class="icon-widget">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" viewBox="0 0 64 64">
                                                    <path fill="var(--bs-primary)"
                                                        d="M32 29a13 13 0 1 0-13-13 13.015 13.015 0 0 0 13 13zm0-24a11 11 0 1 1-11 11A11.013 11.013 0 0 1 32 5zm11.993 26H20.007A9.018 9.018 0 0 0 11 40.007V60a1 1 0 0 0 1 1h40a1 1 0 0 0 1-1V40.007A9.018 9.018 0 0 0 43.993 31zM32 44.487l3.1 11.129-3.1 2.368-3.1-2.368zm.061-4.72c-.021 0-.04-.01-.061-.01s-.04.009-.061.01L29.171 37 32 34.171 34.829 37zM13 40.007A7.015 7.015 0 0 1 20.007 33h10.336l-3.293 3.293a1 1 0 0 0 0 1.414l3.724 3.724-3.98 14.3a1 1 0 0 0 .356 1.062L30.036 59H19.169V46a1 1 0 0 0-2 0v13H13zM51 59h-3.831V46a1 1 0 0 0-2 0v13h-11.2l2.886-2.206a1 1 0 0 0 .356-1.062l-3.98-14.3 3.724-3.724a1 1 0 0 0 0-1.414L33.657 33h10.336A7.015 7.015 0 0 1 51 40.007z" />
                                                    <path
                                                        d="M43 42h-4a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-5h2v5a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1Z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}
                            <div class="col-6 px-2">
                                <div class="card"
                                    style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                                                                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            {{-- <h6 class="text-pink mb-0">Open Projects 05</h6> --}}
                                            <div class="progress-widget">
                                                <div class="">
                                                    <h4 class="counter text-white mb-2">{{ $count['collection'] }}</h4>
                                                    <p class="text-white mb-0">Collection</p>
                                                </div>
                                            </div>
                                            <div class="icon-widget">

                                                <svg fill="var(--bs-primary)" version="1.1" id="Capa_1"
                                                    xmlns="http://www.w3.org/2000/svg" width="40"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 464.544 464.544"
                                                    xml:space="preserve">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <path
                                                                        d="M226.246,246.141c-7.002-8.572-16.13-13.281-25.793-13.281c-9.662,0-18.79,4.708-25.792,13.281 c-19.308,6.842-31.26,20.493-31.26,35.784s11.952,28.941,31.26,35.784c7.001,8.572,16.129,13.28,25.792,13.28 c9.664,0,18.792-4.708,25.793-13.28c19.309-6.841,31.261-20.492,31.261-35.784C257.506,266.634,245.555,252.982,226.246,246.141z ">
                                                                    </path>
                                                                    <path
                                                                        d="M303.514,260.889c-16.02,0-29.051,9.438-29.051,21.036s13.031,21.036,29.051,21.036s29.051-9.438,29.051-21.036 S319.533,260.889,303.514,260.889z">
                                                                    </path>
                                                                    <path
                                                                        d="M97.392,260.889c-16.019,0-29.052,9.438-29.052,21.036s13.033,21.036,29.052,21.036s29.051-9.438,29.051-21.036 S113.411,260.889,97.392,260.889z">
                                                                    </path>
                                                                </g>
                                                                <path
                                                                    d="M455.811,149.25L85.665,68.983c-5.979-1.297-11.878,2.5-13.175,8.479l-19.64,90.571H11.079 C4.96,168.033,0,172.992,0,179.112v205.623c0,6.119,4.96,11.079,11.079,11.079h378.747c6.119,0,11.08-4.959,11.08-11.079v-14.317 l6.631,1.438c5.98,1.297,11.879-2.5,13.176-8.479l43.576-200.954C465.588,156.444,461.789,150.546,455.811,149.25z M378.748,335.281c-4.227-2.439-9.131-3.844-14.361-3.844c-15.895,0-28.777,12.885-28.777,28.778 c0,4.855,1.207,9.429,3.332,13.441H61.966c2.124-4.013,3.332-8.585,3.332-13.441c0-15.894-12.885-28.778-28.778-28.778 c-5.232,0-10.135,1.404-14.362,3.844V228.568c4.227,2.44,9.129,3.845,14.362,3.845c15.893,0,28.778-12.886,28.778-28.779 c0-4.856-1.208-9.429-3.332-13.442h276.976c-2.125,4.013-3.332,8.585-3.332,13.442c0,15.894,12.883,28.779,28.777,28.779 c5.23,0,10.135-1.405,14.361-3.845V335.281L378.748,335.281z M75.522,168.034l8.142-37.544c3.614,3.281,8.107,5.692,13.222,6.8 c15.532,3.369,30.854-6.494,34.223-22.026c1.029-4.746,0.818-9.471-0.409-13.843l270.685,58.698 c-2.371,2.812-4.221,6.117-5.389,9.791l0.002,0.001c-1.764-1.185-3.887-1.878-6.172-1.878L75.522,168.034L75.522,168.034z M409.539,310.351c-2.486-2.258-5.398-4.09-8.633-5.383V196.591c3.988,5.005,9.658,8.72,16.398,10.182 c5.113,1.109,10.201,0.776,14.85-0.712L409.539,310.351z">
                                                                </path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 px-2">
                                <div class="card"
                                    style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                                                                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            {{-- <h6 class="text-pink mb-0">Open Projects 05</h6> --}}
                                            <div class="progress-widget">
                                                <div class="">
                                                    <h4 class="counter text-white mb-2">{{ $count['earning'] }}</h4>
                                                    <p class="text-white mb-0">Earning</p>
                                                </div>
                                            </div>
                                            <div class="icon-widget">

                                                <svg fill="var(--bs-primary)" version="1.1" id="Capa_1"
                                                    xmlns="http://www.w3.org/2000/svg" width="40"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 464.544 464.544"
                                                    xml:space="preserve">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <path
                                                                        d="M226.246,246.141c-7.002-8.572-16.13-13.281-25.793-13.281c-9.662,0-18.79,4.708-25.792,13.281 c-19.308,6.842-31.26,20.493-31.26,35.784s11.952,28.941,31.26,35.784c7.001,8.572,16.129,13.28,25.792,13.28 c9.664,0,18.792-4.708,25.793-13.28c19.309-6.841,31.261-20.492,31.261-35.784C257.506,266.634,245.555,252.982,226.246,246.141z ">
                                                                    </path>
                                                                    <path
                                                                        d="M303.514,260.889c-16.02,0-29.051,9.438-29.051,21.036s13.031,21.036,29.051,21.036s29.051-9.438,29.051-21.036 S319.533,260.889,303.514,260.889z">
                                                                    </path>
                                                                    <path
                                                                        d="M97.392,260.889c-16.019,0-29.052,9.438-29.052,21.036s13.033,21.036,29.052,21.036s29.051-9.438,29.051-21.036 S113.411,260.889,97.392,260.889z">
                                                                    </path>
                                                                </g>
                                                                <path
                                                                    d="M455.811,149.25L85.665,68.983c-5.979-1.297-11.878,2.5-13.175,8.479l-19.64,90.571H11.079 C4.96,168.033,0,172.992,0,179.112v205.623c0,6.119,4.96,11.079,11.079,11.079h378.747c6.119,0,11.08-4.959,11.08-11.079v-14.317 l6.631,1.438c5.98,1.297,11.879-2.5,13.176-8.479l43.576-200.954C465.588,156.444,461.789,150.546,455.811,149.25z M378.748,335.281c-4.227-2.439-9.131-3.844-14.361-3.844c-15.895,0-28.777,12.885-28.777,28.778 c0,4.855,1.207,9.429,3.332,13.441H61.966c2.124-4.013,3.332-8.585,3.332-13.441c0-15.894-12.885-28.778-28.778-28.778 c-5.232,0-10.135,1.404-14.362,3.844V228.568c4.227,2.44,9.129,3.845,14.362,3.845c15.893,0,28.778-12.886,28.778-28.779 c0-4.856-1.208-9.429-3.332-13.442h276.976c-2.125,4.013-3.332,8.585-3.332,13.442c0,15.894,12.883,28.779,28.777,28.779 c5.23,0,10.135-1.405,14.361-3.845V335.281L378.748,335.281z M75.522,168.034l8.142-37.544c3.614,3.281,8.107,5.692,13.222,6.8 c15.532,3.369,30.854-6.494,34.223-22.026c1.029-4.746,0.818-9.471-0.409-13.843l270.685,58.698 c-2.371,2.812-4.221,6.117-5.389,9.791l0.002,0.001c-1.764-1.185-3.887-1.878-6.172-1.878L75.522,168.034L75.522,168.034z M409.539,310.351c-2.486-2.258-5.398-4.09-8.633-5.383V196.591c3.988,5.005,9.658,8.72,16.398,10.182 c5.113,1.109,10.201,0.776,14.85-0.712L409.539,310.351z">
                                                                </path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 px-2">
                                <div class="card"
                                    style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                                                                                    background: linear-gradient(230deg, #00b7ff00, var(--bs-primary));">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="progress-widget">
                                                <div class="">
                                                    <h4 class="counter text-white mb-2">{{ $count['currentOrder'] }}
                                                    </h4>
                                                    <p class="text-white mb-0">Live Orders</p>
                                                </div>
                                            </div>
                                            <div class="icon-widget">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" viewBox="0 0 64 64">
                                                    <path fill="var(--bs-primary)"
                                                        d="M21 49h-1a10 10 0 0 1-10-10v-4a1 1 0 0 1 1-1h19a1 1 0 0 1 1 1v4a10 10 0 0 1-10 10zm-9-13v3a8 8 0 0 0 8 8h1a8 8 0 0 0 8-8v-3zm6.12-7.12a1 1 0 0 1-1-1c0-1.67 1.47-2.45 2.53-3s1.47-.81 1.47-1.24-.55-.76-1.47-1.25-2.53-1.34-2.53-3 1.47-2.44 2.53-3 1.47-.81 1.47-1.24a1 1 0 0 1 2 0c0 1.66-1.46 2.44-2.53 3-.91.48-1.47.81-1.47 1.24s.56.76 1.47 1.25c1.07.57 2.53 1.34 2.53 3s-1.46 2.44-2.53 3c-.91.49-1.47.81-1.47 1.25a1 1 0 0 1-1 .99z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M11.35 43H7.7a3.51 3.51 0 0 1-3.26-2.21A3.52 3.52 0 0 1 7.67 36h3.32a1 1 0 0 1 0 2h-3.3a1.51 1.51 0 0 0-1.25.68A1.51 1.51 0 0 0 7.7 41h3.65a1 1 0 1 1 0 2zM60 52H4a1 1 0 0 1 0-2h56a1 1 0 0 1 0 2zm-6 5H16.1a1 1 0 0 1 0-2H54a1 1 0 0 1 0 2zm-42 0h-2a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M8.27 52a1 1 0 0 1-.92-.61l-1.27-3A1 1 0 0 1 7 47h13a1 1 0 0 1 0 2H8.51l.68 1.61a1 1 0 0 1-.53 1.31 1.09 1.09 0 0 1-.39.08Z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M31.73 52a1.09 1.09 0 0 1-.39-.08 1 1 0 0 1-.53-1.31l.68-1.61H20a1 1 0 0 1 0-2h13a1 1 0 0 1 .83.45 1 1 0 0 1 .09.94l-1.27 3a1 1 0 0 1-.92.61zM58 52a1 1 0 0 1-1-1 23 23 0 0 0-37.65-17.73 1 1 0 0 1-1.28-1.54A25 25 0 0 1 59 51a1 1 0 0 1-1 1z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M47.06 35.57a1 1 0 0 1-.62-.22A20 20 0 0 0 37 31.23a1 1 0 0 1-.84-1.14 1 1 0 0 1 1.14-.84 22 22 0 0 1 10.37 4.53 1 1 0 0 1-.62 1.79zm2.8 2.68a1 1 0 0 1-.75-.34l-.47-.53A1 1 0 1 1 50.1 36c.17.19.35.38.52.58a1 1 0 0 1-.1 1.41 1 1 0 0 1-.66.26zM56 40a1 1 0 0 1-1-1V25h-2v6a1 1 0 0 1-2 0v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a1 1 0 0 1-1 1z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M54 25a1 1 0 0 1-1-1V9a1 1 0 0 1 2 0v15a1 1 0 0 1-1 1Z" />
                                                    <path fill="var(--bs-primary)"
                                                        d="M56 18h-4a2 2 0 0 1-2-2V9a1 1 0 0 1 2 0v7h4V9a1 1 0 0 1 2 0v7a2 2 0 0 1-2 2zM45 28a1 1 0 0 1-1-1v-6a1 1 0 0 1 .29-.71c2.72-2.71 1.07-8.44.23-10.77a.78.78 0 0 0-1.52.26V25a1 1 0 0 1-2 0V9.78a2.78 2.78 0 0 1 5.4-.93c.94 2.61 2.72 9-.4 12.54V27a1 1 0 0 1-1 1zm-30-3a1 1 0 0 1-1-1c0-.3-.82-.69-1.42-1-1.09-.48-2.58-1.18-2.58-2.75s1.49-2.28 2.58-2.79c.6-.28 1.42-.66 1.42-1s-.82-.68-1.42-1C11.49 15 10 14.32 10 12.75s1.49-2.27 2.58-2.78C13.18 9.69 14 9.3 14 9a1 1 0 0 1 2 0c0 1.57-1.49 2.27-2.58 2.78-.6.28-1.42.67-1.42 1s.82.68 1.42 1c1.09.51 2.58 1.21 2.58 2.78s-1.49 2.28-2.58 2.79c-.6.28-1.42.66-1.42 1s.82.69 1.42 1C14.51 21.73 16 22.43 16 24a1 1 0 0 1-1 1zm21 3h-4a1 1 0 0 1-1-1v-2a3 3 0 0 1 6 0v2a1 1 0 0 1-1 1zm-3-2h2v-1a1 1 0 0 0-2 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Refund Statistics Cards -->
                            <div class="col-6 px-2">
                                <div class="card"
                                    style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                                                                                    background: linear-gradient(230deg, #ff6b00, #ff4757);">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="progress-widget">
                                                <div class="">
                                                    <h4 class="counter text-white mb-2">{{ $refund_stats['pending_refunds'] ?? 0 }}</h4>
                                                    <p class="text-white mb-0">Pending Refunds</p>
                                                </div>
                                            </div>
                                            <div class="icon-widget">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" viewBox="0 0 24 24" fill="none">
                                                    <path fill="var(--bs-warning)" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2V7zm0 8h2v2h-2v-2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 px-2">
                                <div class="card"
                                    style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%), inset 5px 8px 8px rgba(0, 0, 0, .2), inset -2px -2px 10px hsla(0, 0%, 100%, .2) !important;
                                                                                                    background: linear-gradient(230deg, #ff6348, #e74c3c);">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="progress-widget">
                                                <div class="">
                                                    <h4 class="counter text-white mb-2">{{ \App\CentralLogics\Helpers::format_currency($refund_stats['total_deductions'] ?? 0) }}</h4>
                                                    <p class="text-white mb-0">Total Penalties</p>
                                                </div>
                                            </div>
                                            <div class="icon-widget">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" viewBox="0 0 24 24" fill="none">
                                                    <path fill="var(--bs-danger)" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                    <path fill="var(--bs-danger)" d="M14 7h-4v2h4V7zm0 4h-4v2h4v-2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Refund Quick Access -->
                        <div class="row mt-3 d-none">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">{{ __('Refund Management') }}</h5>
                                        <a href="{{ route('vendor.refund.index') }}" class="btn btn-primary btn-sm">
                                            <i class="tio-visible"></i> {{ __('View All Refunds') }}
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-primary">{{ $refund_stats['total_refunds'] ?? 0 }}</h4>
                                                    <p class="text-muted mb-0">{{ __('Total Refunds') }}</p>
                                                </div>
                                            </div>
                                           
                                            <div class="col-md-3">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-danger">{{ \App\CentralLogics\Helpers::format_currency($refund_stats['total_deductions'] ?? 0) }}</h4>
                                                    <p class="text-muted mb-0">{{ __('Refund Amount') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-3">
            <div class="d-flex justify-content-between mb-4">
                {{-- <div class="text-success">Outlet Online</div> --}}
                <h4 class="fw-bolder">All Orders</h4>

                <div class="position-relative d-none">
                    <div class="bg-white px-2 py-1 rounded-2 shadow">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="position-absolute top-0 end-0" style="font-size: 10px;">
                        <i class="fas fa-circle text-danger"></i>
                    </div>
                </div>
            </div>
            <div class="input-group search-input rounded-3 d-none">
                <span class="input-group-text border-0" id="search-input">
                    <svg class="icon-18" width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></circle>
                        <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
                <input type="search" class="form-control border-0" placeholder="Search...">
            </div>
            {{-- Desktop View --}}
            <div class="bd-example mt-3 d-lg-block d-none" id="desktop-view-letest-orders">
                <nav>
                    <div class="nav nav-tabs mb-3 bg-transparent d-flex justify-content-between" id="nav-tab"
                        role="tablist">
                        <button class="nav-link flex-fill text-center active" id="nav-home-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                            aria-selected="true">Preparing <span class="ms-2 px-2 py-1">17</span></button>
                        <button class="nav-link flex-fill text-center" id="nav-profile-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                            aria-selected="false">Ready <span class="ms-2 px-2 py-1">17</span></button>
                        <button class="nav-link flex-fill text-center" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Picked Up</button>
                        <button class="nav-link flex-fill text-center" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Delayed</button>
                        <button class="nav-link flex-fill text-center" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Cancelled</button>
                        <button class="nav-link flex-fill text-center" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Delivered</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="card">
                            <div class="card-content">
                                <div class="left">
                                    <div class="delivery-tag zomato mb-2">ZOMATO DELIVERY</div>
                                    <strong>Kebab & Curry</strong>

                                    <span style="color:#666; font-size: 14px;">Sector 43, Gurgaon</span>
                                    <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                    <strong>ID: 174404 0262</strong>
                                    <small>Rahul’s 3rd order</small>
                                    <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                    <div class="timeline">
                                        <div class="timeline-line" style="height: calc(100% - 33px);"></div>
                                        <div class="timeline-item completed">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-label">Order Placed</span>
                                                <span class="timeline-time">2:00 PM</span>
                                            </div>
                                        </div>
                                        <div class="timeline-item completed">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-label">Order Accepted</span>
                                                <span class="timeline-time">2:02 PM</span>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-label">Order Prepared</span>
                                                <span class="timeline-time">--:--</span>
                                            </div>
                                        </div>
                                    </div>
                                    <style>
                                        .timeline {
                                            position: relative;
                                        }

                                        .timeline-line {
                                            position: absolute;
                                            left: 6px;
                                            top: 6px;
                                            bottom: 0;
                                            width: 2px;
                                            background-color: #3a57e8;
                                        }

                                        .timeline-item {
                                            position: relative;
                                            margin-bottom: 20px;
                                        }

                                        .timeline-dot {
                                            top: 4px;
                                            position: absolute;
                                            left: 0;
                                            width: 15px;
                                            height: 15px;
                                            background-color: #ccc;
                                            border-radius: 50%;
                                        }

                                        .timeline-item.completed .timeline-dot {
                                            background-color: var(--bs-primary);
                                        }

                                        .timeline-content {
                                            margin-left: 20px;
                                            display: flex;
                                            justify-content: space-between
                                        }

                                        .timeline-label {
                                            font-weight: bold;
                                            display: block;
                                        }

                                        .timeline-time {
                                            color: #666;
                                        }
                                    </style>
                                </div>
                                <div class="vertical-divider"></div>
                                <div class="middle">
                                    <p style="font-size: 14px;font-weight: bold;" class="d-flex justify-content-between">
                                        <span> 1 x Paneer Kebab</span> <span> ₹405</span>
                                    </p>
                                    <p style="font-size: 14px;font-weight: bold;" class="d-flex justify-content-between">
                                        <span> 1 x Chicken Tikka Kebab </span> <span> ₹405</span>
                                    </p>
                                    <p style="font-weight: bold" class="d-flex justify-content-between">Total bill: ₹850
                                        <span class="paid">PAID</span> <a href="#" class="print-link"> <i
                                                class="fa fa-print"></i> Print bill</a>
                                    </p>
                                    <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                    <div> <span style="font-weight: bold;" class="text-success"> Special Request</span>
                                        <br>
                                        <span class="text-muted">No special request</span>
                                    </div>
                                    {{-- <button class="order-btn">Order ready (12.24)</button> --}}
                                    <button class="order-btn">Order ready (1.24)</button>
                                </div>
                                <div class="vertical-divider"></div>
                                <div class="right">
                                    <div class="mb-3">Delivery partner details</div>
                                    <div class="user-section">
                                        <img src="https://i.pravatar.cc/40" class="user-img">
                                        <div>
                                            <div style="font-weight: bold;">Raghav is on the way</div>
                                            <div class="track-call">
                                                <a href="#"> <i class="fa fa-map-marker"></i> Track</a>
                                                <a href="#"> <i class="fa fa-phone"></i> Call</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3"> <span>Arriving in</span> <span>8
                                            mins</span> </div>
                                    <div class="progress-bar-wrap">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <hr style="border: 1px solid #cecbcb; margin: 20px 0;">
                                    <a href="#" class="support-link fs-6 text-dark"> <i class="fa fa-question-circle"></i>
                                        Support</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <p><strong>This is some placeholder content the Profile tab's associated content.</strong>
                            Clicking
                            another tab will toggle the visibility of this one for the next. The tab JavaScript swaps
                            classes to control the content visibility and styling. You can use it with tabs, pills, and
                            any
                            other <code>.nav</code>-powered navigation.</p>
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <p><strong>This is some placeholder content the Contact tab's associated content.</strong>
                            Clicking
                            another tab will toggle the visibility of this one for the next. The tab JavaScript swaps
                            classes to control the content visibility and styling. You can use it with tabs, pills, and
                            any
                            other <code>.nav</code>-powered navigation.</p>
                    </div>
                </div>
            </div>
            {{-- Phone View --}}
            <div class="bd-example mt-3 d-lg-none d-block" id="mobile-view-letest-orders">
                <nav>
                    <div class="nav nav-tabs mb-3 bg-transparent d-flex justify-content-between" id="nav-tab"
                        role="tablist">
                        <button class="nav-link flex-fill text-center active" id="nav-home-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                            aria-selected="true">Preparing <span class="ms-2 px-2 py-1">17</span></button>
                        <button class="nav-link flex-fill text-center" id="nav-profile-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                            aria-selected="false">Ready <span class="ms-2 px-2 py-1">17</span></button>
                        <button class="nav-link flex-fill text-center" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Picked Up</button>
                        <button class="nav-link flex-fill text-center" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Delayed</button>
                        <button class="nav-link flex-fill text-center" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Cancelled</button>
                        <button class="nav-link flex-fill text-center" id="nav-contact-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                            aria-selected="false">Delivered</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="card">
                            <div class="">
                                <div class="toggle-content" style="display: none;">
                                    <div class="delivery-tag zomato mb-2">ZOMATO DELIVERY</div>
                                    <strong>Kebab & Curry</strong>

                                    <span style="color:#666; font-size: 14px;">Sector 43, Gurgaon</span>
                                    <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                    <div class="timeline">
                                        <div class="timeline-line" style="height: calc(100% - 33px);"></div>
                                        <div class="timeline-item completed">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-label">Order Placed</span>
                                                <span class="timeline-time">2:00 PM</span>
                                            </div>
                                        </div>
                                        <div class="timeline-item completed">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-label">Order Accepted</span>
                                                <span class="timeline-time">2:02 PM</span>
                                            </div>
                                        </div>
                                        <div class="timeline-item mb-0">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-label">Order Prepared</span>
                                                <span class="timeline-time">--:--</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="border: 1px solid #cecbcb;">
                                    <style>
                                        .timeline {
                                            position: relative;
                                        }

                                        .timeline-line {
                                            position: absolute;
                                            left: 6px;
                                            top: 6px;
                                            bottom: 0;
                                            width: 2px;
                                            background-color: #3a57e8;
                                        }

                                        .timeline-item {
                                            position: relative;
                                            margin-bottom: 20px;
                                        }

                                        .timeline-dot {
                                            top: 4px;
                                            position: absolute;
                                            left: 0;
                                            width: 15px;
                                            height: 15px;
                                            background-color: #ccc;
                                            border-radius: 50%;
                                        }

                                        .timeline-item.completed .timeline-dot {
                                            background-color: var(--bs-primary);
                                        }

                                        .timeline-content {
                                            margin-left: 20px;
                                            display: flex;
                                            justify-content: space-between
                                        }

                                        .timeline-label {
                                            font-weight: bold;
                                            display: block;
                                        }

                                        .timeline-time {
                                            color: #666;
                                        }
                                    </style>
                                </div>
                                <div class="">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>ID: 174404 0262</strong> | 6:30 PM<br>
                                            <small class="text-success">Rahul’s 3rd order</small>
                                        </div>
                                        <div class="">
                                            <a href="#" class="print-link fs-3 me-3 text-success toggle-btn"><i
                                                    class="fa fa-cutlery"></i></a>
                                            <a href="#" class="print-link fs-3"> <i class="fa fa-print"></i></a>
                                        </div>
                                    </div>
                                    <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                    <p style="font-size: 14px;font-weight: bold;" class="d-flex justify-content-between">
                                        <span> 1 x Paneer Kebab</span> <span> ₹405</span>
                                    </p>
                                    <p style="font-size: 14px;font-weight: bold;" class="d-flex justify-content-between">
                                        <span> 1 x Chicken Tikka Kebab </span> <span> ₹405</span>
                                    </p>
                                    <div style="font-weight: bold" class="d-flex justify-content-between">
                                        <div>
                                            <div>
                                                Total bill: ₹850 <span class="paid">PAID</span>
                                            </div>
                                            <div class="mt-3"><span style="font-weight: bold;" class="text-success">
                                                    Special
                                                    Request</span> <br>
                                                <span class="text-muted">No special request</span>
                                            </div>
                                        </div>
                                        <a href="" class="toggle-deliveryman-btn">
                                            <img src="{{ asset('assets/images/deliveryman.gif')}}" alt=""
                                                style="height: 128px;">
                                        </a>
                                    </div>
                                    <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                    {{-- <button class="order-btn">Order ready (12.24)</button> --}}
                                    <button class="order-btn">Order ready with (0.24)</button>
                                </div>
                                <div class="toggle-deliveryman mt-4" style="display: none;">
                                    <div class="mb-3">Delivery partner details</div>
                                    <div class="user-section">
                                        <img src="https://i.pravatar.cc/40" class="user-img">
                                        <div>
                                            <div style="font-weight: bold;">Raghav is on the way</div>
                                            <div class="track-call">
                                                <a href="#"> <i class="fa fa-map-marker"></i> Track</a>
                                                <a href="#"> <i class="fa fa-phone"></i> Call</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3"> <span>Arriving in</span> <span>8
                                            mins</span> </div>
                                    <div class="progress-bar-wrap">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <hr style="border: 1px solid #cecbcb; margin: 20px 0;">
                                    <a href="#" class="support-link fs-6 text-dark"> <i class="fa fa-question-circle"></i>
                                        Support</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <p><strong>This is some placeholder content the Profile tab's associated content.</strong>
                            Clicking
                            another tab will toggle the visibility of this one for the next. The tab JavaScript swaps
                            classes to control the content visibility and styling. You can use it with tabs, pills, and
                            any
                            other <code>.nav</code>-powered navigation.</p>
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <p><strong>This is some placeholder content the Contact tab's associated content.</strong>
                            Clicking
                            another tab will toggle the visibility of this one for the next. The tab JavaScript swaps
                            classes to control the content visibility and styling. You can use it with tabs, pills, and
                            any
                            other <code>.nav</code>-powered navigation.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-12">
            <div class="card aos-init aos-animate" data-aos="fade-up" data-aos-delay="600">
                <div class="flex-wrap d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="mb-2 card-title">Most Sold</h4>
                    </div>
                </div>
                <hr style="border: 1px solid #cecbcb; margin: 15px 0;">
                <div class="" id="mostSoldContainer" data-sold="{{json_encode($productSold)}}">
                    <!--div class="mb-2  d-flex profile-media align-items-top">
                        <div class="mt-1 profile-dots-pills border-primary"></div>
                        <div class="ms-4">
                            <h6 class="mb-1 ">foodname</h6>
                            <span class="mb-0">Quantity : 5</span>
                        </div>
                    </div -->
                </div>
                <div class="text-center">
                    <button class="btn btn-primary btn-sm my-2" id="loadMoreButton">See More</button>
                </div>
            </div>
        </div>
    </div>
    
   
    <div id="dm-canvases">

    </div>

    <!-- Custom Delivery Man Arrival Alert Modal -->
    <div id="deliveryArrivalAlert" class="delivery-alert-overlay" style="display: none;">
        <div class="delivery-alert-modal">
            <div class="delivery-alert-header">
                <div class="delivery-alert-icon">
                    <i class="fas fa-motorcycle"></i>
                </div>
                <button class="delivery-alert-close" onclick="closeDeliveryAlert()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="delivery-alert-content">
                <h3 class="delivery-alert-title"></h3>
                <p class="delivery-alert-message"></p>
                <div class="delivery-alert-details">
                    <div class="delivery-alert-eta">
                        <i class="fas fa-clock"></i>
                        <span class="eta-time"></span>
                    </div>
                    <div class="delivery-alert-distance">
                        <i class="fas fa-route"></i>
                        <span class="distance-text"></span>
                    </div>
                </div>
            </div>
            <div class="delivery-alert-actions">
                <button class="btn-track" onclick="trackDeliveryMan()">
                    <i class="fas fa-map-marked-alt"></i> Track Location
                </button>
                <button class="btn-call" onclick="callDeliveryMan()">
                    <i class="fas fa-phone"></i> Call Driver
                </button>
            </div>
        </div>
    </div>

    <!-- Cooking Time Input Modal -->
    <div id="cookingTimeModal" class="delivery-alert-overlay" style="display: none;">
        <div class="delivery-alert-modal" style="max-width: 400px;">
            <div class="delivery-alert-header" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="delivery-alert-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <button class="delivery-alert-close" onclick="closeCookingTimeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="delivery-alert-content">
                <h3 class="delivery-alert-title">Start Processing Order</h3>
                <p class="delivery-alert-message">How long will it take to prepare this order?</p>
                
                <div class="cooking-time-input-section">
                    <label for="cookingTimeInput" class="form-label fw-bold">Cooking Time (minutes):</label>
                    <input type="number" id="cookingTimeInput" class="form-control mb-3" 
                           placeholder="Enter cooking time" min="5" max="180" value="15">
                    
                    <div class="quick-time-buttons mb-3">
                        <button class="btn btn-outline-primary btn-sm me-2 quick-time-btn" data-time="10">10 min</button>
                        <button class="btn btn-outline-primary btn-sm me-2 quick-time-btn" data-time="15">15 min</button>
                        <button class="btn btn-outline-primary btn-sm me-2 quick-time-btn" data-time="20">20 min</button>
                        <button class="btn btn-outline-primary btn-sm me-2 quick-time-btn" data-time="30">30 min</button>
                        <button class="btn btn-outline-primary btn-sm quick-time-btn" data-time="45">45 min</button>
                    </div>
                </div>
            </div>
            <div class="delivery-alert-actions">
                <button class="btn-cancel" onclick="closeCookingTimeModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn-start-cooking" onclick="startProcessingWithTime()">
                    <i class="fas fa-play"></i> Start Cooking
                </button>
            </div>
        </div>
    </div>

@endsection

@push('javascript')
    @vite(['resources/js/app.js'])
    <script src="{{ asset('assets/vendor/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script>
        function order_status_change_alert(route, message, option = {}) {
            if (option.processing == "canceled") {
                Swal.fire({
                    //text: message,
                    title: message,
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Submit',
                    // inputPlaceholder: "Enter processing time",
                    input: 'text',
                    html: '<br/>' + '<label>Enter Cancle reason</label>',
                    // inputValue: ,
                    preConfirm: (cancelReason) => {

                        location.href = route + '&cancel_reason=' + cancelReason;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })
            } else {
                Swal.fire({
                    title: 'Are you sure  ',
                    text: message,
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        location.href = route;
                    }
                })
            }
        }

        function change_order_state() {
            document.querySelectorAll('#new-orders [data-order-state]').forEach(eventButton => {
                eventButton.addEventListener('click', function () {
                    let route = "{{ route('vendor.order.order-status-update') }}?";
                    route += `id=${eventButton.getAttribute('orderId')}&order_status=${eventButton.dataset.orderState}`;

                    let message = '';
                    if (eventButton.dataset.orderState === 'confirmed') {
                        message = "Change status to confirmed?";
                    } else {
                        message = "Are you sure you want to cancel the order?";
                    }
                    order_status_change_alert(route, message, {
                        processing: eventButton.dataset.orderState
                    });
                });
            });
        }
        change_order_state();
    </script>

    <script>
        const mostSoldContainer = document.getElementById("mostSoldContainer");
        const mostSoldItems = JSON.parse(mostSoldContainer.dataset.sold);
        const loadMoreButton = document.getElementById("loadMoreButton");
        let displayedItems = 0; // Counter for displayed items

        // Function to render items
        function renderItems(count) {
            const itemsToDisplay = mostSoldItems.slice(displayedItems, displayedItems + count);
            itemsToDisplay.forEach((item) => {
                const itemDiv = document.createElement("div");
                itemDiv.className = "mb-2 d-flex profile-media align-items-top";
                itemDiv.innerHTML = `
                                    <div class="mt-1 profile-dots-pills border-primary"></div>
                                    <div class="ms-4">
                                        <h6 class="mb-1">${item.foodname}</h6>
                                        <span class="mb-0">Quantity : ${item.quantity}</span>
                                    </div>
                                `;
                mostSoldContainer.appendChild(itemDiv);
            });
            displayedItems += count;

            if (displayedItems >= mostSoldItems.length) {
                loadMoreButton.style.display = "none";
            }
        }
        renderItems(3);
        loadMoreButton.addEventListener("click", () => {
            renderItems(3);
        });
    </script>
    <script>
        document.querySelector('[data-temp="off"]').addEventListener('change', async (event) => {
            let url = `{{ route('vendor.business-settings.temp-off') }}`;
            try {
                const resp = await fetch(url, {
                    method: "POST",
                    body: JSON.stringify({ tempOff: event.target.checked }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!resp.ok) {
                    const error = await resp.json();
                    throw new Error(error.message);
                }
                const result = await resp.json();
                toastr.success(result.message)
                // Handle success if necessary
            } catch (error) {
                console.error('Error:', error);
                toastr.error(error.message || 'An error occurred while updating the setting.');
            }
        });
    </script>
    <script>
        function parseTimeLabel(label) {
           const timeMatch = label.match(/(\d+):(\d+)/);
            if (!timeMatch) return 0;
            const minutes = parseInt(timeMatch[1]);
            const seconds = parseInt(timeMatch[2]);
            return (minutes * 60) + seconds;
        }

        function startProgress(button) {
            const text = button.textContent;
            
            // Parse the current remaining time from button text
            const timeMatch = text.match(/Order ready \((\d+):(\d+)\)/);
            if (!timeMatch) {
                return; // Button doesn't have expected format
            }
            
            const initialMinutes = parseInt(timeMatch[1]);
            const initialSeconds = parseInt(timeMatch[2]);
            let totalRemaining = (initialMinutes * 60) + initialSeconds;
            
            // If time is already 0 or negative, mark as ready immediately
            if (totalRemaining <= 0) {
                button.textContent = "Order Ready";
                button.style.background = `linear-gradient(90deg, var(--bs-success) 100%, #899cff 100%)`;
                
                // Hide extra cooking time section when processing is complete
                const orderCard = button.closest('.card');
                const extraCookingSection = orderCard.querySelector('.extra-cooking-time-section');
                if (extraCookingSection) {
                    extraCookingSection.style.transition = 'opacity 0.5s ease, height 0.5s ease';
                    // extraCookingSection.style.opacity = '0';
                    extraCookingSection.style.display = 'none';
                    extraCookingSection.style.pointerEvents = 'none';
                    
                    // Add a "Processing Complete" message
                    const disabledMessage = document.createElement('small');
                    disabledMessage.className = 'text-muted fst-italic';
                    disabledMessage.textContent = '(Processing completed - Extra time no longer available)';
                    disabledMessage.style.display = 'block';
                    disabledMessage.style.marginTop = '5px';
                    extraCookingSection.appendChild(disabledMessage);
                    
                    // Disable all inputs and buttons
                    extraCookingSection.querySelectorAll('input, button').forEach(el => {
                        el.disabled = true;
                    });
                }
                return;
            }
            
            // Start countdown timer
            const timer = setInterval(() => {
                totalRemaining--;
                
                const minutes = Math.floor(totalRemaining / 60);
                const seconds = totalRemaining % 60;
                const timeText = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                // Calculate progress percentage (assuming max initial time for progress calculation)
                const initialTotal = (initialMinutes * 60) + initialSeconds;
                const elapsed = initialTotal - totalRemaining;
                const percent = initialTotal > 0 ? (elapsed / initialTotal) * 100 : 100;

                button.textContent = `Order ready within (${timeText})`;
                button.style.background = `linear-gradient(90deg, var(--bs-primary) ${percent}%, #899cff ${percent}%)`;

                if (totalRemaining <= 0) {
                    clearInterval(timer);
                    button.textContent = "Order Ready";
                    button.style.background = `linear-gradient(90deg, var(--bs-success) 100%, #899cff 100%)`;
                    
                    // Hide extra cooking time section when processing is complete
                    const orderCard = button.closest('.card');
                    const extraCookingSection = orderCard.querySelector('.extra-cooking-time-section');
                    if (extraCookingSection) {
                        extraCookingSection.style.transition = 'opacity 0.5s ease, height 0.5s ease';
                        extraCookingSection.style.opacity = '0.5';
                        extraCookingSection.style.pointerEvents = 'none';
                        
                        // Add a "Processing Complete" message
                        const disabledMessage = document.createElement('small');
                        disabledMessage.className = 'text-muted fst-italic';
                        disabledMessage.textContent = '(Processing completed - Extra time no longer available)';
                        disabledMessage.style.display = 'block';
                        disabledMessage.style.marginTop = '5px';
                        extraCookingSection.appendChild(disabledMessage);
                        
                        // Disable all inputs and buttons
                        extraCookingSection.querySelectorAll('input, button').forEach(el => {
                            el.disabled = true;
                        });
                    }
                }
            }, 1000);
        }

        document.querySelectorAll('.order-btn').forEach(startProgress);
    </script>
    <script>
        const letest_orders= JSON.parse(document.querySelector('meta[name=latest_orders]').getAttribute('content'))
        const orderStatusKeys = [...new Set(letest_orders.map(order => order.order_status))];
        const desktop_view_letest_orders_container = document.getElementById('desktop-view-letest-orders');
        const mobile_view_letest_orders_container = document.getElementById('mobile-view-letest-orders');

        // Step 2: Filter orders by each order_status
        const filteredOrdersByStatus = {};

        orderStatusKeys.forEach(status => {
            filteredOrdersByStatus[status] = letest_orders.filter(order => order.order_status === status);
        });

        function rederDesktopOrderTabs(orderStatusKeys, filteredOrdersByStatus) {
            const navContainer = document.createElement('nav');
            const tabContainer = document.createElement('div');

            tabContainer.className = "nav nav-tabs mb-3 bg-transparent d-flex justify-content-between";
            tabContainer.id = "nav-tab";
            tabContainer.setAttribute("role", "tablist");

            orderStatusKeys.forEach((status, index) => {
                const btn = document.createElement('button');
                btn.className = `nav-link flex-fill text-center ${index === 0 ? 'active' : ''}`;
                btn.id = `nav-${status}-tab`;
                btn.setAttribute("data-bs-toggle", "tab");
                btn.setAttribute("data-bs-target", `#nav-${status}`);
                btn.setAttribute("type", "button");
                btn.setAttribute("role", "tab");
                btn.setAttribute("aria-controls", `nav-${status}`);
                btn.setAttribute("aria-selected", index === 0 ? "true" : "false");
                btn.innerHTML = `${status.charAt(0).toUpperCase() + status.slice(1)} <span class="ms-2 px-2 py-1">${filteredOrdersByStatus[status].length}</span>`;
                tabContainer.appendChild(btn);
            });

            navContainer.appendChild(tabContainer);
            desktop_view_letest_orders_container.innerHTML = ''; // Clear previous content
            desktop_view_letest_orders_container.appendChild(navContainer);
            renderDesktopTabOrders(orderStatusKeys, filteredOrdersByStatus, desktop_view_letest_orders_container);
        }
        function rederMobileOrderTabs(orderStatusKeys, filteredOrdersByStatus) {
            const navContainer = document.createElement('nav');
            const tabContainer = document.createElement('div');

            tabContainer.className = "nav nav-tabs mb-3 bg-transparent d-flex justify-content-between";
            tabContainer.id = "nav-tab-mobile";
            tabContainer.setAttribute("role", "tablist");

            orderStatusKeys.forEach((status, index) => {
                const btn = document.createElement('button');
                btn.className = `nav-link flex-fill text-center ${index === 0 ? 'active' : ''}`;
                btn.id = `nav-${status}-mobile-tab`;
                btn.setAttribute("data-bs-toggle", "tab");
                btn.setAttribute("data-bs-target", `#nav-${status}-mobile`);
                btn.setAttribute("type", "button");
                btn.setAttribute("role", "tab");
                btn.setAttribute("aria-controls", `nav-${status}-mobile`);
                btn.setAttribute("aria-selected", index === 0 ? "true" : "false");
                btn.innerHTML = `${status.charAt(0).toUpperCase() + status.slice(1)} <span class="ms-2 px-2 py-1">${filteredOrdersByStatus[status].length}</span>`;
                tabContainer.appendChild(btn);
            });

            navContainer.appendChild(tabContainer);
            mobile_view_letest_orders_container.innerHTML = ''; // Clear previous content
            mobile_view_letest_orders_container.appendChild(navContainer);
            renderMobileTabOrders(orderStatusKeys, filteredOrdersByStatus, mobile_view_letest_orders_container);
        }
        rederDesktopOrderTabs(orderStatusKeys, filteredOrdersByStatus);
        rederMobileOrderTabs(orderStatusKeys, filteredOrdersByStatus);
        function renderDesktopTabOrders(orderStatusKeys, filteredOrdersByStatus,container) {

            // Create <div class="tab-content">
            const tabContent = document.createElement('div');
            tabContent.className = 'tab-content';
            tabContent.id = 'nav-tabContent';


            orderStatusKeys.forEach((status, index) => {
                const orders = filteredOrdersByStatus[status];

                // --- Create tab pane ---
                const tabPane = document.createElement('div');
                tabPane.className = `tab-pane fade ${index === 0 ? 'show active' : ''}`;
                tabPane.id = `nav-${status}`;
                tabPane.setAttribute('role', 'tabpanel');
                tabPane.setAttribute('aria-labelledby', `nav-${status}-tab`);

                // Add order cards inside this tab pane
                orders.forEach(order => {
                    const r_add = JSON.parse(order.restaurant_address);
                    const r_full_address = `${r_add.street}, ${r_add.city}, ${r_add.city}, ${r_add.pincode}`;
                    const r_order_details = JSON.parse(order.restaurantData);
                    const food_items = r_order_details?.foodItemList || [];
                    const timeline = [];
                    if (order.pending) timeline.push({ key: 'Order Placed', value: formatTo12Hour(order.pending) });
                    if (order.accepted) timeline.push({ key: 'Order Accepted', value: formatTo12Hour(order.accepted) });
                    if (order.confirmed) timeline.push({ key: 'Order Confirmed', value: formatTo12Hour(order.confirmed) });
                    if (order.processing) timeline.push({ key: 'Order Processing', value: formatTo12Hour(order.processing) });
                    if (order.handover) timeline.push({ key: 'Order Handover', value: formatTo12Hour(order.handover) });
                    if (order.dm_at_restaurant) timeline.push({ key: 'Delivery Partner at Restaurant', value: formatTo12Hour(order.dm_at_restaurant) });
                    if (order.picked_up) timeline.push({ key: 'Order Picked Up', value: formatTo12Hour(order.picked_up) });
                    if (order.order_on_way) timeline.push({ key: 'Order On Way', value: formatTo12Hour(order.order_on_way) });
                    if (order.arrived_at_door) timeline.push({ key: 'Delivery Partner at Door', value: formatTo12Hour(order.arrived_at_door) });
                    if (order.delivered) timeline.push({ key: 'Order Delivered', value: formatTo12Hour(order.delivered) });
                    if (order.canceled) timeline.push({ key: 'Order Canceled', value: formatTo12Hour(order.canceled) });

                    // formatTo12Hour


                    const card = document.createElement('div');
                    card.className = 'card my-3';


                    card.innerHTML = `
                        <div class="card-content">
                            <div class="left">
                                <div class="delivery-tag zomato mb-2">.. DELIVERY</div>
                                <strong>${order.restaurant_name}</strong>

                                <span style="color:#666; font-size: 14px;">${r_full_address}</span>
                                <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                <strong>ID: #${order.id}</strong>
                                <small>Rahul’s 3rd order</small>
                                <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                <div class="timeline">
                                    <div class="timeline-line" style="height: calc(100% - 33px);"></div>`+
                                    timeline.map((item, index) => {
                                        return `
                                        <div class="timeline-item ${index === 0 ? 'completed' : ''}">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-label">${item.key}</span>
                                                <span class="timeline-time">${item.value}</span>
                                            </div>
                                        </div>`;
                                    }).join('')+`
                                </div>
                                <style>
                                    .timeline {
                                        position: relative;
                                    }

                                    .timeline-line {
                                        position: absolute;
                                        left: 6px;
                                        top: 6px;
                                        bottom: 0;
                                        width: 2px;
                                        background-color: #3a57e8;
                                    }

                                    .timeline-item {
                                        position: relative;
                                        margin-bottom: 20px;
                                    }

                                    .timeline-dot {
                                        top: 4px;
                                        position: absolute;
                                        left: 0;
                                        width: 15px;
                                        height: 15px;
                                        background-color: #ccc;
                                        border-radius: 50%;
                                    }

                                    .timeline-item.completed .timeline-dot {
                                        background-color: var(--bs-primary);
                                    }

                                    .timeline-content {
                                        margin-left: 20px;
                                        display: flex;
                                        justify-content: space-between
                                    }

                                    .timeline-label {
                                        font-weight: bold;
                                        display: block;
                                    }

                                    .timeline-time {
                                        color: #666;
                                    }
                                </style>
                            </div>
                            <div class="vertical-divider"></div>
                            <div class="middle">`+
                                 food_items.map((item) => {
                                    return `
                                    <p style="font-size: 14px;font-weight: bold;" class="d-flex justify-content-between">
                                        <span>${item.quantity} x ${item.foodName}</span> <span>₹${item.restaurantPriceAfterPackingCharge}</span>
                                    </p>`;
                                }).join('')+`

                                <p style="font-weight: bold" class="d-flex justify-content-between">Total bill: ₹${r_order_details?.receivableAmount ||0}
                                    <span class="paid">${order.payment_status}</span> <a href=" {!!url('/') !!}/restaurant-panel/order/generate-invoice/${order.id}" class="print-link"> <i
                                            class="fa fa-print"></i> Print bill</a>
                                </p>
                                <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                <div> <span style="font-weight: bold;" class="text-success"> Special Request</span>
                                    <br>
                                    <span class="text-muted">${order.cooking_instruction ?? 'No special request'}</span>
                                </div>
                                ${order.confirmed && !order.processing && !order.picked_up && !order.delivered && !order.canceled ?
                                    `<div class="processing-section mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span style="font-weight: 600; color: #495057;">Start Cooking Process</span>
                                            <button class="btn btn-success btn-sm start-processing-btn" data-order-id="${order.id}">
                                                <i class="fas fa-play"></i> Start Processing
                                            </button>
                                        </div>
                                    </div>` : ''
                                }
                                ${ (order.processing != null && order.processing != null  && order.picked_up == null) || (order.delivered = null && order.canceled == null)?
                                    `<div class="processing-time-section mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="processing-time-info">
                                                <span style="font-weight: 600; color: #495057;">Processing Time</span>
                                                <small class="d-block text-muted">Base: ${order.processing_time}min + Extra: ${order.extra_cooking_time || 0}min = Total: ${parseInt(order.processing_time || 0) + parseInt(order.extra_cooking_time || 0)}min</small>
                                            </div>
                                            <div class="d-flex gap-2 align-items-center">
                                                <button class="order-btn">Order ready (${getRemainingProcessingTime(order.processing, order.processing_time, order.extra_cooking_time || 0).formatted})</button>
                                                <button class="btn btn-sm btn-warning force-ready-btn" data-order-id="${order.id}" title="Force mark as ready">
                                                    <i class="fas fa-check-circle"></i> Force Ready
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="extra-cooking-time-section mt-3" data-order-id="${order.id}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span style="font-weight: 600; color: #495057;">Extra Cooking Time</span>
                                            <span class="extra-time-display badge bg-info">${order.extra_cooking_time || 0} min</span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-sm extra-cooking-input" 
                                                       placeholder="Minutes" min="0" max="300" value="${order.extra_cooking_time || 0}">
                                                <button class="btn btn-sm btn-primary update-extra-cooking" type="button">
                                                    <i class="fas fa-clock"></i> Update
                                                </button>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-warning add-extra-time" data-minutes="5">+5</button>
                                                <button class="btn btn-sm btn-outline-warning add-extra-time" data-minutes="10">+10</button>
                                                <button class="btn btn-sm btn-outline-warning add-extra-time" data-minutes="15">+15</button>
                                            </div>
                                        </div>
                                    </div>`: ''
                                }
                                ${order.handover && !order.picked_up && !order.delivered && !order.canceled ?
                                    `<div class="handover-section mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="handover-info">
                                                <span style="font-weight: 600; color: #28a745;">Order Ready for Handover</span>
                                                <small class="d-block text-muted">Ready since: ${formatTo12Hour(order.handover)}</small>
                                            </div>
                                            <button class="btn btn-primary btn-sm handover-btn" data-order-id="${order.id}">
                                                <i class="fas fa-hand-paper"></i> Mark as Handed Over
                                            </button>
                                        </div>
                                    </div>` : ''
                                }
                            </div>
                            <div class="vertical-divider"></div>
                            ${order.delivery_man_id != null? `
                            <div class="right">
                                <div class="mb-3">Delivery partner details</div>
                                <div class="user-section">
                                    <img src="${order.deliveryman_image}" class="user-img">
                                    <div>
                                        <div style="font-weight: bold;">${order.deliveryman_name} ${order.picked_up != null ? '' : 'is on the way'}</div>
                                        <div class="track-call">
                                        ${ order.picked_up == null ?`<a href="javascript:void(0)" type="button" data-bs-toggle="offcanvas" data-bs-target="#dmLocation-${order.id}" aria-controls="dmLocation" >
                                                <i class="fas fa-map-marked-alt"></i> Track
                                            </a>`: ''}
                                            <a href="tel:${order.deliveryman_phone}"> <i class="fa fa-phone"></i> Call</a>
                                        </div>
                                    </div>
                                </div>
                                ${order.picked_up == null && order.delivery_man_id ? `
                                    <div class="d-flex justify-content-between align-items-center mt-3 p-2 rounded" style="background-color: #f8f9fa;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-motorcycle text-primary me-2"></i>
                                            <span class="fw-bold">Delivery Partner ETA:</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="eta-text text-primary">Loading...</span>
                                            <span class="dm-status badge bg-secondary ms-2">Checking...</span>
                                        </div>
                                    </div>
                                    <div class="dm-arrival-time" 
                                         data-order-id="${order.id}" 
                                         data-info='${JSON.stringify(order.gmapData || {})}' 
                                         style="display: none;">
                                    </div>
                                    <div class="progress-bar-wrap mt-2">
                                        <div class="progress-bar bg-primary" style="width: 0%; height: 5px;"></div>
                                    </div>
                                ` : (order.picked_up == null ? `
                                    <div class="d-flex justify-content-center mt-3 p-2 rounded" style="background-color: #fff3cd;">
                                        <div class="text-warning">
                                            <i class="fas fa-clock me-2"></i>
                                            <span>Waiting for delivery partner assignment</span>
                                        </div>
                                    </div>
                                ` : '')}
                                <hr style="border: 1px solid #cecbcb; margin: 20px 0;">
                                <a href="#" class="support-link fs-6 text-dark"> <i class="fa fa-question-circle"></i>
                                    Support</a>

                            </div>`: ''
                            }
                        </div>
                    `;

                    tabPane.appendChild(card);
                    render_dm_locationMap(order);
                });

                tabContent.appendChild(tabPane);
            });

            container.appendChild(tabContent);

            container.querySelectorAll('.dm-arrival-time[data-info]').forEach(showDmArrivalTime); // this is for shwoing delivery man arrrival time
            container.querySelectorAll('.order-btn').forEach(startProgress); // this is for showing order ready time
        }
        function renderMobileTabOrders(orderStatusKeys, filteredOrdersByStatus,container) {

            // Create <div class="tab-content">
            const tabContent = document.createElement('div');
            tabContent.className = 'tab-content';
            tabContent.id = 'nav-tabContent-mobile';


            orderStatusKeys.forEach((status, index) => {
                const orders = filteredOrdersByStatus[status];

                // --- Create tab pane ---
                const tabPane = document.createElement('div');
                tabPane.className = `tab-pane fade ${index === 0 ? 'show active' : ''}`;
                tabPane.id = `nav-${status}-mobile`;
                tabPane.setAttribute('role', 'tabpanel');
                tabPane.setAttribute('aria-labelledby', `nav-${status}-mobile-tab`);

                // Add order cards inside this tab pane
                orders.forEach(order => {
                    const r_add = JSON.parse(order.restaurant_address);
                    const r_full_address = `${r_add.street}, ${r_add.city}, ${r_add.city}, ${r_add.pincode}`;
                    const r_order_details = JSON.parse(order.restaurantData);
                    const food_items = r_order_details?.foodItemList || [];
                    const timeline = [];
                    if (order.pending) timeline.push({ key: 'Order Placed', value: formatTo12Hour(order.pending) });
                    if (order.accepted) timeline.push({ key: 'Order Accepted', value: formatTo12Hour(order.accepted) });
                    if (order.confirmed) timeline.push({ key: 'Order Confirmed', value: formatTo12Hour(order.confirmed) });
                    if (order.processing) timeline.push({ key: 'Order Processing', value: formatTo12Hour(order.processing) });
                    if (order.handover) timeline.push({ key: 'Order Handover', value: formatTo12Hour(order.handover) });
                    if (order.dm_at_restaurant) timeline.push({ key: 'Delivery Partner at Restaurant', value: formatTo12Hour(order.dm_at_restaurant) });
                    if (order.picked_up) timeline.push({ key: 'Order Picked Up', value: formatTo12Hour(order.picked_up) });
                    if (order.order_on_way) timeline.push({ key: 'Order On Way', value: formatTo12Hour(order.order_on_way) });
                    if (order.arrived_at_door) timeline.push({ key: 'Delivery Partner at Door', value: formatTo12Hour(order.arrived_at_door) });
                    if (order.delivered) timeline.push({ key: 'Order Delivered', value: formatTo12Hour(order.delivered) });
                    if (order.canceled) timeline.push({ key: 'Order Canceled', value: formatTo12Hour(order.canceled) });

                    // formatTo12Hour


                    const card = document.createElement('div');
                    card.className = 'card my-3';

                    card.innerHTML = `
                        <div class="">
                            <div class="toggle-content" style="display: none;">
                                <div class="delivery-tag zomato mb-2">.. DELIVERY</div>
                                <strong>${order.restaurant_name}</strong>

                                <span style="color:#666; font-size: 14px;">${r_full_address}</span>
                                <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                <div class="timeline">
                                    <div class="timeline-line" style="height: calc(100% - 33px);"></div>`+
                                    timeline.map((item, index) => {
                                        return `
                                        <div class="timeline-item ${index === 0 ? 'completed' : ''}">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-label">${item.key}</span>
                                                <span class="timeline-time">${item.value}</span>
                                            </div>
                                        </div>`;
                                    }).join('')+`
                                </div>
                                <hr style="border: 1px solid #cecbcb;">
                                <style>
                                    .timeline {
                                        position: relative;
                                    }

                                    .timeline-line {
                                        position: absolute;
                                        left: 6px;
                                        top: 6px;
                                        bottom: 0;
                                        width: 2px;
                                        background-color: #3a57e8;
                                    }

                                    .timeline-item {
                                        position: relative;
                                        margin-bottom: 20px;
                                    }

                                    .timeline-dot {
                                        top: 4px;
                                        position: absolute;
                                        left: 0;
                                        width: 15px;
                                        height: 15px;
                                        background-color: #ccc;
                                        border-radius: 50%;
                                    }

                                    .timeline-item.completed .timeline-dot {
                                        background-color: var(--bs-primary);
                                    }

                                    .timeline-content {
                                        margin-left: 20px;
                                        display: flex;
                                        justify-content: space-between
                                    }

                                    .timeline-label {
                                        font-weight: bold;
                                        display: block;
                                    }

                                    .timeline-time {
                                        color: #666;
                                    }
                                </style>
                            </div>
                            <div class="">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>ID: #${order.id}</strong> | ${formatTo12Hour(order.created_at)}<br>
                                        <small class="text-success">Rahul’s 3rd order</small>
                                    </div>
                                    <div class="">
                                        <a href="#" class="print-link fs-3 me-3 text-success toggle-btn"><i
                                                class="fa fa-cutlery"></i></a>
                                        <a href=""{!!url('/') !!}/restaurant-panel/order/generate-invoice/${order.id}"" class="print-link fs-3"> <i class="fa fa-print"></i></a>
                                    </div>
                                </div>
                                <hr style="border: 1px solid #cecbcb; margin: 3px 0;">`+
                                food_items.map((item) => {
                                return `
                                <p style="font-size: 14px;font-weight: bold;" class="d-flex justify-content-between">
                                    <span>${item.quantity} x ${item.foodName}</span> <span>₹${item.restaurantPriceAfterPackingCharge}</span>
                                </p>`;
                                }).join('')+`
                                <div style="font-weight: bold" class="d-flex justify-content-between">
                                    <div>
                                        <div>
                                            Total bill: ₹${r_order_details?.receivableAmount ||0} <span class="paid">${order.payment_status}</span>
                                        </div>
                                        <div class="mt-3"><span style="font-weight: bold;" class="text-success">
                                                Special
                                                Request</span> <br>
                                            <span class="text-muted">${order.cooking_instruction ?? 'No special request'}</span>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" class="toggle-deliveryman-btn-${order.id} text-dark">
                                        <img src="{{ asset('assets/images/deliveryman.gif')}}" alt=""
                                            style="height: 128px;">
                                    </a>
                                </div>
                                <hr style="border: 1px solid #cecbcb; margin: 3px 0;">
                                ${order.confirmed && !order.processing && !order.picked_up && !order.delivered && !order.canceled ?
                                    `<div class="processing-section mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span style="font-weight: 600; color: #495057;">Start Cooking Process</span>
                                            <button class="btn btn-success btn-sm start-processing-btn" data-order-id="${order.id}">
                                                <i class="fas fa-play"></i> Start Processing
                                            </button>
                                        </div>
                                    </div>` : ''
                                }
                                ${ order.processing != null && order.processing != null ?
                                `<div class="processing-time-section mb-3">
                                    <div class="processing-time-info mb-2">
                                        <span style="font-weight: 600; color: #495057;">Processing Time</span>
                                        <small class="d-block text-muted">Base: ${order.processing_time}min + Extra: ${order.extra_cooking_time || 0}min = Total: ${parseInt(order.processing_time || 0) + parseInt(order.extra_cooking_time || 0)}min</small>
                                    </div>
                                    <div class="d-flex gap-2 mb-2">
                                        <button class="order-btn flex-grow-1">Order ready (${getRemainingProcessingTime(order.processing, order.processing_time, order.extra_cooking_time || 0).formatted})</button>
                                        <button class="btn btn-sm btn-warning force-ready-btn" data-order-id="${order.id}" title="Force mark as ready">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="extra-cooking-time-section mt-3" data-order-id="${order.id}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span style="font-weight: 600; color: #495057;">Extra Cooking Time</span>
                                        <span class="extra-time-display badge bg-info">${order.extra_cooking_time || 0} min</span>
                                    </div>
                                    <div class="d-flex gap-2 flex-column">
                                        <div class="d-flex gap-2">
                                            <input type="number" class="form-control form-control-sm extra-cooking-input flex-grow-1" 
                                                   placeholder="Minutes" min="0" max="300" value="${order.extra_cooking_time || 0}">
                                            <button class="btn btn-sm btn-primary update-extra-cooking">
                                                <i class="fas fa-clock"></i> Update
                                            </button>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-warning add-extra-time" data-minutes="5">+5 min</button>
                                            <button class="btn btn-sm btn-outline-warning add-extra-time" data-minutes="10">+10 min</button>
                                            <button class="btn btn-sm btn-outline-warning add-extra-time" data-minutes="15">+15 min</button>
                                        </div>
                                    </div>
                                </div>`: ''}
                                ${order.handover && !order.picked_up && !order.delivered && !order.canceled ?
                                    `<div class="handover-section mt-3">
                                        <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background-color: #d4edda; border: 1px solid #c3e6cb;">
                                            <div class="handover-info">
                                                <span style="font-weight: 600; color: #28a745;"><i class="fas fa-check-circle me-1"></i>Ready for Handover</span>
                                                <small class="d-block text-muted">Ready since: ${formatTo12Hour(order.handover)}</small>
                                            </div>
                                            <button class="btn btn-primary btn-sm handover-btn" data-order-id="${order.id}">
                                                <i class="fas fa-hand-paper"></i> Handover
                                            </button>
                                        </div>
                                    </div>` : ''
                                }
                            </div>
                            <div class="toggle-deliveryman-${order.id} mt-4" style="display: none;">
                                ${order.delivery_man_id != null? `
                                <div class="mb-3">Delivery partner details</div>
                                <div class="user-section">
                                    <img src="${order.deliveryman_image}" class="user-img">
                                    <div>
                                        <div style="font-weight: bold;">${order.deliveryman_name} ${order.picked_up != null ? '' : 'is on the way'}</div>
                                        <div class="track-call">
                                        ${ order.picked_up == null ?`<a href="javascript:void(0)" type="button" data-bs-toggle="offcanvas" data-bs-target="#dmLocation-${order.id}" aria-controls="dmLocation" >
                                                <i class="fas fa-map-marked-alt"></i> Track
                                            </a>`: ''}
                                            <a href="tel:${order.deliveryman_phone}"> <i class="fa fa-phone"></i> Call</a>
                                        </div>
                                    </div>
                                </div>
                                ${order.picked_up == null ? `
                                <div class="dm-arrival-container mt-3" style="background: #f8f9fa; padding: 10px; border-radius: 8px; border-left: 4px solid #28a745;">
                                    <div class="d-flex justify-content-between align-items-center dm-arrival-time"
                                        data-info='${JSON.stringify(order.gmapData)}'>
                                        <div>
                                            <i class="fas fa-motorcycle text-success me-2"></i>
                                            <span style="font-weight: 600; color: #495057;">Arriving in</span>
                                        </div>
                                        <div class="eta-display">
                                            <span class="eta-text badge bg-success">Loading...</span>
                                            <small class="text-muted d-block mt-1 distance-text"></small>
                                        </div>
                                    </div>
                                    <div class="progress-bar-wrap mt-2" style="background: #e9ecef; border-radius: 2px; height: 2px;">
                                        <div class="progress-bar" style="width: 0%; height: 2px; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 2px; transition: width 0.3s ease;"></div>
                                    </div>
                                </div>
                                ` : ''}
                                <hr style="border: 1px solid #cecbcb; margin: 20px 0;">
                                <a href="#" class="support-link fs-6 text-dark"> <i class="fa fa-question-circle"></i>
                                    Support</a>
                                ` : ''}
                            </div>
                        </div>`;


                    tabPane.appendChild(card);

                });

                tabContent.appendChild(tabPane);
            });

            container.appendChild(tabContent);

            container.querySelectorAll('.dm-arrival-time[data-info]').forEach(showDmArrivalTime); // this is for shwoing delivery man arrrival time
            container.querySelectorAll('.order-btn').forEach(startProgress); // this is for showing order ready time
        }


        function render_dm_locationMap(order) {
            this.order = order;
            let dm_location =  JSON.parse(this.order?.deliveryman_location || '{}') ;
            const dmCanvases = document.getElementById('dm-canvases');
            dmCanvases.insertAdjacentHTML('beforeend', `
            <div class="offcanvas offcanvas-bottom fullscreen " tabindex="-1" id="dmLocation-${this.order.id}" aria-labelledby="offcanvasBottomLabel">
                <button class="position-absolute top-0 end-0 text-primary me-2 mt-2 fa fa-close fa-2x" style="z-index: 1;"
                    data-bs-dismiss="offcanvas"></button>
                <div class="offcanvas-body m-1 p-1 ">
                    <div class="mapouter m-0 p-0">
                        <iframe src="https://www.google.com/maps?q=${dm_location?.lat},${dm_location?.lng}&hl=es;z=14&output=embed"
                            style="border:0;width:100%;height:100%;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>`);
        }

        function showDmArrivalTime(el) {
            try {
                const orderId = el.dataset.orderId;
                if (!orderId) return;

                // Initialize arrival display
                updateDeliveryManArrival(orderId, el);
                
                // Set up real-time updates every 30 seconds
                const intervalId = setInterval(() => {
                    updateDeliveryManArrival(orderId, el);
                }, 30000);

                // Store interval ID for cleanup
                el.dataset.intervalId = intervalId;

            } catch (err) {
                console.error('Error initializing delivery man arrival:', err);
            }
        }

        async function updateDeliveryManArrival(orderId, element) {
            try {
                const response = await fetch(`{{ route('vendor.delivery-man-arrival') }}?order_id=${orderId}`);
                const data = await response.json();

                if (data.success && data.arrival_data) {
                    const arrivalData = data.arrival_data;
                    
                    // Update ETA text
                    const etaElement = element.querySelector('.eta-text');
                    if (etaElement) {
                        const etaMinutes = arrivalData.eta_minutes;
                        if (etaMinutes <= 0) {
                            etaElement.textContent = 'Overdue';
                            etaElement.className = 'eta-text text-danger fw-bold';
                        } else if (etaMinutes <= 2) {
                            etaElement.textContent = `${Math.ceil(etaMinutes)} min (Arriving Soon!)`;
                            etaElement.className = 'eta-text text-warning fw-bold';
                        } else {
                            etaElement.textContent = arrivalData.duration_text;
                            etaElement.className = 'eta-text text-primary';
                        }
                    }

                    // Update status badge
                    const statusElement = element.querySelector('.dm-status');
                    if (statusElement && arrivalData.status) {
                        statusElement.textContent = arrivalData.status.label;
                        statusElement.className = `dm-status badge bg-${arrivalData.status.color} ms-2`;
                    }

                    // Update progress bar
                    const progressBar = element.nextElementSibling?.querySelector('.progress-bar');
                    if (progressBar) {
                        const etaMinutes = arrivalData.eta_minutes;
                        const maxTime = 30; // 30 minutes max expected time
                        const progressPercent = Math.max(0, Math.min(100, ((maxTime - etaMinutes) / maxTime) * 100));
                        
                        progressBar.style.width = `${progressPercent}%`;
                        
                        // Change color based on urgency
                        progressBar.className = 'progress-bar';
                        if (etaMinutes <= 0) progressBar.classList.add('bg-danger');
                        else if (etaMinutes <= 2) progressBar.classList.add('bg-warning');
                        else if (etaMinutes <= 5) progressBar.classList.add('bg-info');
                        else progressBar.classList.add('bg-success');
                    }

                    // Show notification for urgent arrivals
                    if (arrivalData.eta_minutes <= 2 && arrivalData.eta_minutes > 0) {
                        showArrivalNotification(arrivalData, 'warning');
                    } else if (arrivalData.eta_minutes <= 0) {
                        showArrivalNotification(arrivalData, 'danger');
                    }

                } else if (data.success && !data.arrival_data) {
                    // Delivery man not assigned or already picked up
                    const etaElement = element.querySelector('.eta-text');
                    if (etaElement) {
                        etaElement.textContent = 'Not Available';
                        etaElement.className = 'eta-text text-muted';
                    }
                    
                    // Clear interval since no DM is assigned
                    if (element.dataset.intervalId) {
                        clearInterval(parseInt(element.dataset.intervalId));
                        delete element.dataset.intervalId;
                    }
                }
            } catch (error) {
                console.error('Error updating delivery man arrival:', error);
            }
        }

        let currentDeliveryData = null; // Store current delivery data for actions

        function showArrivalNotification(arrivalData, type) {
            currentDeliveryData = arrivalData; // Store for action buttons
            
            const modal = document.getElementById('deliveryArrivalAlert');
            const title = modal.querySelector('.delivery-alert-title');
            const message = modal.querySelector('.delivery-alert-message');
            const etaTime = modal.querySelector('.eta-time');
            const distanceText = modal.querySelector('.distance-text');
            
            // Set content based on type
            if (type === 'danger') {
                title.textContent = 'Delivery Man Overdue!';
                message.textContent = `${arrivalData.delivery_man.name} should have arrived by now. Please check their status.`;
                etaTime.textContent = 'Overdue';
                etaTime.style.color = '#dc3545';
                
                // Change modal header to red gradient for overdue
                modal.querySelector('.delivery-alert-header').style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
            } else {
                title.textContent = 'Delivery Man Arriving Soon!';
                message.textContent = `${arrivalData.delivery_man.name} is on the way to your location.`;
                etaTime.textContent = `${Math.ceil(arrivalData.eta_minutes)} min`;
                etaTime.style.color = '#28a745';
                
                // Keep default green gradient
                modal.querySelector('.delivery-alert-header').style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            }
            
            // Set distance if available
            if (arrivalData.distance) {
                distanceText.textContent = `${arrivalData.distance} away`;
            } else {
                distanceText.textContent = 'Calculating...';
            }
            
            // Show the modal with animation
            modal.style.display = 'flex';
            
            // Auto-hide after 8 seconds for non-overdue alerts
            if (type !== 'danger') {
                setTimeout(() => {
                    closeDeliveryAlert();
                }, 8000);
            }
        }

        function closeDeliveryAlert() {
            const modal = document.getElementById('deliveryArrivalAlert');
            modal.style.display = 'none';
        }

        function trackDeliveryMan() {
            if (currentDeliveryData && currentDeliveryData.order_id) {
                // Find and trigger the track button for this order
                const trackButton = document.querySelector(`[data-bs-target="#dmLocation-${currentDeliveryData.order_id}"]`);
                if (trackButton) {
                    trackButton.click();
                    closeDeliveryAlert();
                } else {
                    // Fallback: open Google Maps with delivery man location
                    if (currentDeliveryData.delivery_man_location) {
                        const location = currentDeliveryData.delivery_man_location;
                        window.open(`https://www.google.com/maps?q=${location.lat},${location.lng}`, '_blank');
                        closeDeliveryAlert();
                    }
                }
            }
        }

        function callDeliveryMan() {
            if (currentDeliveryData && currentDeliveryData.delivery_man.phone) {
                window.location.href = `tel:${currentDeliveryData.delivery_man.phone}`;
                closeDeliveryAlert();
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('deliveryArrivalAlert');
            if (e.target === modal) {
                closeDeliveryAlert();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeliveryAlert();
            }
        });

        // Extra Cooking Time Functions
        function initializeExtraCookingTimeControls() {
            // Handle update button clicks
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('update-extra-cooking') || e.target.closest('.update-extra-cooking')) {
                    const button = e.target.classList.contains('update-extra-cooking') ? e.target : e.target.closest('.update-extra-cooking');
                    const section = button.closest('.extra-cooking-time-section');
                    
                    // Check if processing is complete (section is disabled)
                    if (section.style.pointerEvents === 'none' || button.disabled) {
                        if (typeof toastr !== 'undefined') {
                            toastr.warning('Cannot update extra cooking time - processing is already complete');
                        }
                        return;
                    }
                    
                    const orderId = section.dataset.orderId;
                    const input = section.querySelector('.extra-cooking-input');
                    const extraTime = parseInt(input.value) || 0;
                    
                    updateExtraCookingTime(orderId, extraTime, section);
                }

                // Handle quick add buttons
                if (e.target.classList.contains('add-extra-time')) {
                    const button = e.target;
                    const section = button.closest('.extra-cooking-time-section');
                    
                    // Check if processing is complete (section is disabled)
                    if (section.style.pointerEvents === 'none' || button.disabled) {
                        if (typeof toastr !== 'undefined') {
                            toastr.warning('Cannot add extra cooking time - processing is already complete');
                        }
                        return;
                    }
                    
                    const minutes = parseInt(button.dataset.minutes);
                    const orderId = section.dataset.orderId;
                    const input = section.querySelector('.extra-cooking-input');
                    const currentTime = parseInt(input.value) || 0;
                    const newTime = currentTime + minutes;
                    
                    input.value = newTime;
                    updateExtraCookingTime(orderId, newTime, section);
                }
            });

            // Handle enter key in input
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.classList.contains('extra-cooking-input')) {
                    const input = e.target;
                    const section = input.closest('.extra-cooking-time-section');
                    const orderId = section.dataset.orderId;
                    const extraTime = parseInt(input.value) || 0;
                    
                    updateExtraCookingTime(orderId, extraTime, section);
                }
            });
        }

        async function updateExtraCookingTime(orderId, extraTime, section) {
            try {
                const button = section.querySelector('.update-extra-cooking');
                const originalText = button.innerHTML;
                
                // Show loading state
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                button.disabled = true;

                const response = await fetch(`{{ route('vendor.extra-cooking-time.update') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        extra_cooking_time: extraTime
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update extra time display
                    const display = section.querySelector('.extra-time-display');
                    display.textContent = `${extraTime} min`;
                    display.className = extraTime > 0 ? 'extra-time-display badge bg-warning' : 'extra-time-display badge bg-info';
                    
                    // Update processing time info display
                    const orderCard = section.closest('.card');
                    const processingTimeInfo = orderCard.querySelector('.processing-time-info small');
                    if (processingTimeInfo) {
                        // Extract base time from current display or assume default
                        const baseTimeMatch = processingTimeInfo.textContent.match(/Base: (\d+)min/);
                        const baseTime = baseTimeMatch ? parseInt(baseTimeMatch[1]) : 15;
                        const totalTime = baseTime + extraTime;
                        
                        processingTimeInfo.textContent = `Base: ${baseTime}min + Extra: ${extraTime}min = Total: ${totalTime}min`;
                    }
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(`Extra cooking time updated to ${extraTime} minutes. New total: ${(data.data.new_extra_time || 0) + (data.data.base_time || 15)} minutes`);
                    }
                    
                    // Log for debugging
                    console.log('Extra cooking time updated:', data.data);
                } else {
                    throw new Error(data.message || 'Failed to update extra cooking time');
                }

            } catch (error) {
                console.error('Error updating extra cooking time:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error(error.message || 'Failed to update extra cooking time');
                }
            } finally {
                // Reset button state
                const button = section.querySelector('.update-extra-cooking');
                button.innerHTML = '<i class="fas fa-clock"></i> Update';
                button.disabled = false;
            }
        }

        async function loadExtraCookingTime(orderId) {
            try {
                const response = await fetch(`{{ route('vendor.extra-cooking-time.get') }}?order_id=${orderId}`);
                const data = await response.json();
                
                if (data.success) {
                    return data.data;
                }
            } catch (error) {
                console.error('Error loading extra cooking time:', error);
                return null;
            }
        }

        // Initialize extra cooking time controls when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeExtraCookingTimeControls();
            initializeProcessingControls();
            initializeForceReadyControls();
            initializeHandoverControls();
        });

        let currentProcessingOrderId = null;

        // Force Ready Functionality
        function initializeForceReadyControls() {
            // Handle force ready button clicks
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('force-ready-btn') || e.target.closest('.force-ready-btn')) {
                    const button = e.target.classList.contains('force-ready-btn') ? e.target : e.target.closest('.force-ready-btn');
                    const orderId = button.dataset.orderId;
                    showForceReadyConfirmation(orderId, button);
                }
            });
        }

        function showForceReadyConfirmation(orderId, button) {
            // Create confirmation modal
            const modal = document.createElement('div');
            modal.className = 'delivery-alert-overlay';
            modal.style.display = 'flex';
            modal.innerHTML = `
                <div class="delivery-alert-modal" style="max-width: 400px;">
                    <div class="delivery-alert-header" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                        <div class="delivery-alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4 class="delivery-alert-title text-dark">Force Mark as Ready?</h4>
                        <button class="delivery-alert-close" onclick="this.closest('.delivery-alert-overlay').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="delivery-alert-content">
                        <p class="delivery-alert-message">
                            Are you sure you want to force mark this order as ready? This will bypass the normal cooking timer and immediately mark the order as completed.
                        </p>
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> This action cannot be undone and will stop the cooking timer.
                        </div>
                    </div>
                    <div class="delivery-alert-actions">
                        <button class="btn-cancel" onclick="this.closest('.delivery-alert-overlay').remove()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button class="btn-force-ready" onclick="forceMarkAsReady('${orderId}', this)">
                            <i class="fas fa-check-circle"></i> Force Ready
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Close on outside click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }

        async function forceMarkAsReady(orderId, button) {
            try {
                const originalText = button.innerHTML;
                
                // Show loading state
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                button.disabled = true;

                const response = await fetch(`{{ route('vendor.force-ready') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        order_id: orderId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Close modal
                    button.closest('.delivery-alert-overlay').remove();
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Order has been force marked as ready!');
                    }
                    
                    // Find and update the order button immediately
                    const orderCard = document.querySelector(`[data-order-id="${orderId}"]`)?.closest('.card');
                    if (orderCard) {
                        const orderBtn = orderCard.querySelector('.order-btn');
                        const forceBtn = orderCard.querySelector('.force-ready-btn');
                        
                        if (orderBtn) {
                            orderBtn.textContent = "Order Ready";
                            orderBtn.style.background = `linear-gradient(90deg, var(--bs-success) 100%, #899cff 100%)`;
                        }
                        
                        if (forceBtn) {
                            forceBtn.style.display = 'none';
                        }
                        
                        // Hide extra cooking time section
                        const extraCookingSection = orderCard.querySelector('.extra-cooking-time-section');
                        if (extraCookingSection) {
                            extraCookingSection.style.transition = 'opacity 0.5s ease, height 0.5s ease';
                            extraCookingSection.style.opacity = '0.5';
                            extraCookingSection.style.pointerEvents = 'none';
                            
                            // Add disabled message
                            const disabledMessage = document.createElement('small');
                            disabledMessage.className = 'text-muted fst-italic';
                            disabledMessage.textContent = '(Order force marked as ready - Extra time no longer available)';
                            disabledMessage.style.display = 'block';
                            disabledMessage.style.marginTop = '5px';
                            extraCookingSection.appendChild(disabledMessage);
                            
                            // Disable all inputs and buttons
                            extraCookingSection.querySelectorAll('input, button').forEach(el => {
                                el.disabled = true;
                            });
                        }
                    }
                    
                } else {
                    throw new Error(data.message || 'Failed to force mark order as ready');
                }

            } catch (error) {
                console.error('Error force marking order as ready:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error(error.message || 'Failed to force mark order as ready');
                }
            } finally {
                // Reset button state if still visible
                if (button && !button.closest('.delivery-alert-overlay').removed) {
                    button.innerHTML = '<i class="fas fa-check-circle"></i> Force Ready';
                    button.disabled = false;
                }
            }
        }

        // Handover Controls Functions
        function initializeHandoverControls() {
            // Handle handover button clicks
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('handover-btn') || e.target.closest('.handover-btn')) {
                    const button = e.target.classList.contains('handover-btn') ? e.target : e.target.closest('.handover-btn');
                    const orderId = button.dataset.orderId;
                    showHandoverConfirmation(orderId, button);
                }
            });
        }

        function showHandoverConfirmation(orderId, button) {
            // Create confirmation modal
            const modal = document.createElement('div');
            modal.className = 'delivery-alert-overlay';
            modal.style.display = 'flex';
            modal.innerHTML = `
                <div class="delivery-alert-modal" style="max-width: 400px;">
                    <div class="delivery-alert-header" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                        <div class="delivery-alert-icon">
                            <i class="fas fa-hand-paper"></i>
                        </div>
                        <h4 class="delivery-alert-title">Confirm Handover</h4>
                        <button class="delivery-alert-close" onclick="this.closest('.delivery-alert-overlay').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="delivery-alert-content">
                        <p class="delivery-alert-message">
                            Are you sure you want to mark this order as handed over to the delivery partner?
                        </p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> This will update the order status and notify the delivery partner.
                        </div>
                    </div>
                    <div class="delivery-alert-actions">
                        <button class="btn-cancel" onclick="this.closest('.delivery-alert-overlay').remove()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button class="btn-handover" onclick="processHandover('${orderId}', this)">
                            <i class="fas fa-hand-paper"></i> Confirm Handover
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Close on outside click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }

        async function processHandover(orderId, button) {
            try {
                const originalText = button.innerHTML;
                
                // Show loading state
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                button.disabled = true;

                const response = await fetch(`{{ route('vendor.handover') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        order_id: orderId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Close modal
                    button.closest('.delivery-alert-overlay').remove();
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Order has been marked as handed over to delivery partner!');
                    }
                    
                    // Refresh the page to show updated order status
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                    
                } else {
                    throw new Error(data.message || 'Failed to process handover');
                }

            } catch (error) {
                console.error('Error processing handover:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error(error.message || 'Failed to process handover');
                }
            } finally {
                // Reset button state if still visible
                if (button && !button.closest('.delivery-alert-overlay').removed) {
                    button.innerHTML = '<i class="fas fa-hand-paper"></i> Confirm Handover';
                    button.disabled = false;
                }
            }
        }

        // Processing Controls Functions
        function initializeProcessingControls() {
            // Handle start processing button clicks
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('start-processing-btn') || e.target.closest('.start-processing-btn')) {
                    const button = e.target.classList.contains('start-processing-btn') ? e.target : e.target.closest('.start-processing-btn');
                    const orderId = button.dataset.orderId;
                    showCookingTimeModal(orderId);
                }

                // Handle quick time buttons
                if (e.target.classList.contains('quick-time-btn')) {
                    const time = parseInt(e.target.dataset.time);
                    const input = document.getElementById('cookingTimeInput');
                    input.value = time;
                    
                    // Update active button
                    document.querySelectorAll('.quick-time-btn').forEach(btn => btn.classList.remove('active'));
                    e.target.classList.add('active');
                }
            });

            // Handle enter key in cooking time input
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.id === 'cookingTimeInput') {
                    startProcessingWithTime();
                }
            });
        }

        function showCookingTimeModal(orderId) {
            currentProcessingOrderId = orderId;
            const modal = document.getElementById('cookingTimeModal');
            
            // Reset form
            const input = document.getElementById('cookingTimeInput');
            input.value = 15;
            document.querySelectorAll('.quick-time-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector('.quick-time-btn[data-time="15"]')?.classList.add('active');
            
            modal.style.display = 'flex';
            input.focus();
        }

        function closeCookingTimeModal() {
            const modal = document.getElementById('cookingTimeModal');
            modal.style.display = 'none';
            currentProcessingOrderId = null;
        }

        async function startProcessingWithTime() {
            if (!currentProcessingOrderId) return;
            
            const input = document.getElementById('cookingTimeInput');
            const cookingTime = parseInt(input.value);
            
            if (!cookingTime || cookingTime < 5 || cookingTime > 180) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Please enter a valid cooking time between 5 and 180 minutes');
                }
                return;
            }

            try {
                const startButton = document.querySelector('.btn-start-cooking');
                const originalText = startButton.innerHTML;
                
                // Show loading state
                startButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting...';
                startButton.disabled = true;

                const response = await fetch(`{{ route('vendor.start-processing') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        order_id: currentProcessingOrderId,
                        cooking_time: cookingTime
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Close modal
                    closeCookingTimeModal();
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(`Order processing started! Cooking time: ${cookingTime} minutes`);
                    }
                    
                    // Refresh the page to show updated order status
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                    
                } else {
                    throw new Error(data.message || 'Failed to start processing');
                }

            } catch (error) {
                console.error('Error starting processing:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error(error.message || 'Failed to start processing');
                }
            } finally {
                // Reset button state
                const startButton = document.querySelector('.btn-start-cooking');
                startButton.innerHTML = '<i class="fas fa-play"></i> Start Cooking';
                startButton.disabled = false;
            }
        }

        // Close cooking time modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('cookingTimeModal');
            if (e.target === modal) {
                closeCookingTimeModal();
            }
        });

        // Close cooking time modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('cookingTimeModal').style.display === 'flex') {
                closeCookingTimeModal();
            }
        });

        // renderDesktopTabOrders();
        function formatTo12Hour(timeString) {
            const date = new Date(timeString);
            if (isNaN(date)) return 'Invalid Date';

            let hours = date.getHours();
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12; // hour '0' should be '12'

            return `${hours}:${minutes} ${ampm}`;
        }
        function getRemainingProcessingTime(processing, processingTimeMinutes, extraCookingTime = 0) {
            const processingDate = new Date(processing);
            const now = new Date();

            // Calculate total cooking time (base + extra)
            const totalCookingTime = parseInt(processingTimeMinutes) +  parseInt(extraCookingTime);

            // Calculate the target completion time
            const targetTime = new Date(processingDate.getTime() + totalCookingTime * 60000);

            // Calculate total remaining time in milliseconds
            let remainingMs = targetTime - now;

            // If time has passed, return 0 minutes and 0 seconds
            if (remainingMs <= 0) {
                return { 
                    minutes: 0, 
                    seconds: 0,
                    formatted: `0:00`,
                    totalTime: totalCookingTime,
                    baseTime: processingTimeMinutes,
                    extraTime: extraCookingTime
                };
            }

            const minutes = Math.floor(remainingMs / 60000);
            const seconds = Math.floor((remainingMs % 60000) / 1000);

            return { 
                minutes: minutes, 
                seconds : seconds,
                formatted: `${minutes}:${seconds.toString().padStart(2, '0')}`,
                totalTime: totalCookingTime,
                baseTime: processingTimeMinutes,
                extraTime: extraCookingTime
            };
        }



    </script>


    <script>
        document.querySelectorAll('[class^="toggle-deliveryman-btn-"]').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                const orderId = this.className.match(/toggle-deliveryman-btn-(\d+)/)?.[1];
                if (!orderId) return;

                const content = document.querySelector(`.toggle-deliveryman-${orderId}`);
                if (content) {
                    content.style.display = content.style.display === 'none' ? 'block' : 'none';
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userId = "{{ auth('vendor')->user()->id }}";
            window.Echo.private(`vendor.${userId}`)
            .listen('.order.placed', (e) => {
                console.log('New Order Received:');
                console.log('Order ID:', e.order_id);
                console.log('Instructions:', e.instructions);
                console.log('Amount:', e.amount);
                console.log('Placed at:', e.placed_at);
            });

        })
    </script>
@endpush
