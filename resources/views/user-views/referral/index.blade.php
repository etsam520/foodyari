@extends('user-views.restaurant.layouts.main')

@section('title', 'Referral Program')

@push('css')
<style>
    body {
        background: #fff6ea61;
        min-height: 100vh;
        font-family: 'Poppins', 'Segoe UI', sans-serif;
    }

    .referral-container {
        max-width: 1200px;
        margin: 0 auto;
        /* padding: 20px; */
        position: relative;
    }

    .referral-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 60vh;
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        background: #ff810a;
        z-index: -1;
        border-radius: 0 0 50px 50px;
        clip-path: ellipse(100% 100% at 50% 0%);
    }

    .referral-card {
        /* background: rgba(255, 255, 255, 0.95); */
        color: #333;
        border-radius: 25px;
        padding: 0;
        margin-bottom: 30px;
        text-align: center;
        /* box-shadow: 0 15px 35px rgba(102, 126, 234, 0.2); */
        /* border: 1px solid rgba(255, 255, 255, 0.8); */
        position: relative;
        overflow: hidden;
        min-height: 600px;
        /* backdrop-filter: blur(10px); */
    }

    .earnings-header {
        display: none;
    }

    .earnings-label {
        background: rgba(255, 255, 255, 0.25);
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .referral-header {
        padding: 60px 30px 30px 30px;
        position: relative;
        text-align: center;
    }

    .referral-card h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 30px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        line-height: 1.2;
        margin-top: 0;
        color: #fff;
    }

    .friends-illustration {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 30px;
        /* margin: 30px auto; */
        position: relative;
        /* background: linear-gradient(135deg, #667eea, #764ba2); */
        /* padding: 40px; */
        border-radius: 20px;
        /* border: 1px solid rgba(255, 255, 255, 0.2); */
        /* max-width: 350px; */
        /* box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); */
    }

    .friend-avatar {
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        border: 3px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: transform 0.3s ease;
    }

    .friend-avatar:hover {
        transform: translateY(-5px);
    }

    .money-coins {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-left: 15px;
    }

    .coin {
        font-size: 1.2rem;
        animation: float 2s ease-in-out infinite;
    }

    .coin:nth-child(2) {
        animation-delay: 0.5s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }

    .referral-description {
        background: white;
        color: #666;
        margin: 30px;
        padding: 25px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.8);
    }

    .referral-description p {
        font-size: 1rem;
        line-height: 1.6;
        margin: 0;
        text-align: center;
        font-weight: 500;
    }

    .process-steps {
        display: flex;
        justify-content: space-around;
        margin: 0;
        gap: 25px;
        padding: 30px 30px;
        /* background: rgba(255, 255, 255, 0.8); */
        border-radius: 20px;
        margin: 0 12px;
        /* box-shadow: 0 5px 20px rgba(0,0,0,0.08); */
    }

    .step-item {
        flex: 1;
        text-align: center;
        max-width: 90px;
    }

    .step-circle {
        margin: 0 auto 15px;
    }

    .step-icon {
        width: 55px;
        height: 55px;
        background: white;
        color: #ff810a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border: none;
    }

    .step-icon i {
        font-size: 1.3rem;
        color: #667eea;
    }

    .step-item p {
        font-size: 0.75rem;
        margin: 0;
        line-height: 1.3;
        color: #fff;
        font-weight: 500;
    }

    .referral-code-section {
        margin: 30px;
        padding: 35px 30px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 6px 25px rgba(255, 129, 10, 0.1);
        border: 1px solid rgba(255, 129, 10, 0.1);
        position: relative;
    }

    .referral-code-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #ff810a, #ffb347);
        border-radius: 20px 20px 0 0;
    }

    .referral-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #ff810a;
        margin-bottom: 20px;
        text-align: center;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .referral-code-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .code-display-box {
        background: linear-gradient(135deg, #fff5eb, #fff9f0);
        border-radius: 15px;
        padding: 5px;
        border: 2px solid rgba(255, 129, 10, 0.2);
        box-shadow: 0 4px 15px rgba(255, 129, 10, 0.1);
    }

    .referral-code {
        background: white;
        color: #ff810a;
        padding: 20px 40px;
        border-radius: 12px;
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: 4px;
        text-align: center;
        user-select: all;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        min-width: 280px;
        margin: 0;
        display: block;
        box-shadow: 0 2px 8px rgba(255, 129, 10, 0.1);
    }

    .referral-code:hover {
        border-color: #ff810a;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 129, 10, 0.2);
        background: linear-gradient(135deg, #fff5eb, #ffffff);
    }

    .tap-to-copy {
        background: linear-gradient(135deg, #ff810a, #ff9500);
        color: white;
        padding: 16px 40px;
        border-radius: 30px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-align: center;
        border: none;
        box-shadow: 0 5px 15px rgba(255, 129, 10, 0.3);
    }

    .tap-to-copy:hover {
        background: linear-gradient(135deg, #ff9500, #ffb347);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 129, 10, 0.4);
    }

    .refer-now-section {
        margin: 0;
        padding: 20px 25px 35px 25px;
    }

    .refer-now-btn {
        background: #667eea;
        color: white;
        border: none;
        border-radius: 30px;
        padding: 18px 45px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        width: 100%;
        max-width: 300px;
    }

    .refer-now-btn:hover {
        background: #5a6fd8;
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }

    .refer-now-btn i {
        margin-right: 8px;
    }

    .terms {
        font-size: 0.8rem;
        opacity: 0.8;
        margin: 8px 0 0 0;
        text-align: center;
        font-weight: 500;
    }

    .code-success-message {
        margin-top: 15px;
        color: #28a745;
        font-size: 0.95rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        opacity: 0;
        animation: fadeInOut 3s ease-in-out;
        background: linear-gradient(135deg, #f0fff4, #e6fff0);
        padding: 12px 20px;
        border-radius: 12px;
        border: 1px solid #28a745;
    }

    .code-success-message i {
        color: #28a745;
        font-size: 1.1rem;
    }

    @keyframes fadeInOut {
        0%, 100% { opacity: 0; transform: translateY(10px); }
        20%, 80% { opacity: 1; transform: translateY(0); }
    }

    .share-buttons {
        margin-top: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .share-label {
        font-size: 1rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
        margin-right: 5px;
    }

    .share-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 12px;
        border-radius: 50%;
        font-size: 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .share-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px) scale(1.1);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .share-btn i {
        font-size: 1.2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(255, 129, 10, 0.08);
        /* border-left: 5px solid transparent; */
        /* border-image: linear-gradient(135deg, #ff810a, #ffb347) 1; */
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(255, 129, 10, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ff810a, #ffb347);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: white;
    }

    .stat-icon i {
        font-size: 1.8rem;
        color: white;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        display: block;
        font-size: 0.9rem;
        color: #666;
        font-weight: 900;
        margin-bottom: 8px;
        text-transform: capitalize;
        letter-spacing: 0.3px;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 600;
        background: linear-gradient(135deg, #ff810a, #ff9500);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
    }

    .rewards-sections-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        /* margin-bottom: 30px; */
    }

    .rewards-section {
        background: white;
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0 6px 25px rgba(255, 129, 10, 0.1);
        margin-bottom: 0;
        border: 1px solid rgba(255, 129, 10, 0.1);
        height: fit-content;
        min-height: 300px;
        display: flex;
        flex-direction: column;
    }

    .rewards-section h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .referral-history h3 {
        font-size: 1.1rem !important;
        font-weight: 600;
        margin-bottom: 20px !important;
        color: #333;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .rewards-section h3 i,
    .referral-history h3 i {
        color: #ff810a;
        font-size: 1.2rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .rewards-section h3 div {
        line-height: 1.3;
    }

    .rewards-section small {
        font-size: 0.75rem;
        color: #888;
        font-weight: 400;
        display: block;
        margin-top: 3px;
        line-height: 1.2;
    }

    .reward-item {
        background: #fafafa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid #ff810a;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .reward-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 129, 10, 0.15);
        background: #f8f9fa;
    }

    .reward-item.unlocked {
        border-left-color: #28a745;
        background: linear-gradient(135deg, #f8fff9, #f0fff4);
    }

    .reward-item.claimed {
        border-left-color: #6c757d;
        background: #f5f5f5;
        opacity: 0.8;
    }

    .reward-item.pending {
        border-left-color: #ffc107;
        background: linear-gradient(135deg, #fffdf0, #fffbf0);
    }

    .reward-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .reward-badge.pending {
        background: linear-gradient(135deg, #ffc107, #ffb347);
        color: #212529;
    }

    .reward-badge.unlocked {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .reward-badge.claimed {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }

    .reward-item h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        margin-top: 0;
    }

    .reward-item p {
        font-size: 0.85rem;
        line-height: 1.4;
        margin-bottom: 12px;
    }

    .progress-bar-wrapper {
        margin: 15px 0;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }

    .progress-bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .progress-bar.bg-success {
        background: linear-gradient(90deg, #28a745, #20c997);
    }

    .progress-bar.bg-warning {
        background: linear-gradient(90deg, #ffc107, #fd7e14);
    }

    .progress-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 25%, rgba(255,255,255,0.2) 25%, rgba(255,255,255,0.2) 50%, transparent 50%, transparent 75%, rgba(255,255,255,0.2) 75%);
        background-size: 20px 20px;
        animation: progressStripes 1s linear infinite;
    }

    @keyframes progressStripes {
        0% { background-position: 0 0; }
        100% { background-position: 20px 0; }
    }

    .referral-history {
        background: white;
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 6px 25px rgba(255, 129, 10, 0.1);
        border: 1px solid rgba(255, 129, 10, 0.1);
    }

    .history-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 1rem;
    }

    .history-item:last-child {
        border-bottom: none;
    }

    .referral-code-group {
        background: #fafafa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #f0f0f0;
    }

    .referral-code-group h6 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
    }

    .uses-list .history-item {
        padding: 12px 0;
        font-size: 0.95rem;
    }

    .claim-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        border-radius: 25px;
        padding: 12px 25px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .claim-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
    }

    .claim-btn:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #888;
    }

    .empty-state i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 15px;
        display: block;
    }

    .empty-state p {
        font-size: 0.95rem;
        line-height: 1.4;
        margin: 0;
        color: #888;
    }

    /* Mobile optimizations - App-like Experience */
    @media (max-width: 768px) {
        body {
            background: #f8f9fa;
        }

        .referral-container {
            padding: 0;
            max-width: 100%;
        }

        .referral-container::before {
            border-radius: 0 0 30px 30px;
            height: 45vh;
        }

        .referral-card {
            margin-bottom: 15px;
            border-radius: 0;
        }

        .referral-header {
            padding: 30px 20px 25px 20px;
        }

        .referral-card h1 {
            font-size: 1.4rem;
            line-height: 1.3;
            margin-bottom: 20px;
            margin-top: 8px;
            font-weight: 700;
        }

        .referral-header img {
            height: 160px !important;
            border-radius: 15px !important;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .process-steps {
            gap: 10px;
            padding: 20px 15px;
            margin: 0;
            background: transparent;
        }

        .step-item {
            max-width: 95px;
        }

        .step-icon {
            width: 48px;
            height: 48px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .step-icon i {
            font-size: 1.2rem;
        }

        .step-item p {
            font-size: 0.7rem;
            line-height: 1.3;
            font-weight: 600;
        }

        .referral-code-section {
            padding: 25px 15px;
            margin: 15px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(255, 129, 10, 0.12);
        }

        .referral-label {
            font-size: 0.7rem;
            margin-bottom: 15px;
        }

        .referral-code {
            font-size: 1.6rem;
            padding: 18px 35px;
            min-width: auto;
            width: 100%;
            letter-spacing: 3px;
            font-weight: 800;
        }

        .tap-to-copy {
            padding: 14px 30px;
            font-size: 0.85rem;
            width: 100%;
            max-width: 280px;
            box-shadow: 0 6px 18px rgba(255, 129, 10, 0.25);
        }

        /* App-like Stats Grid */
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
            padding: 0 15px;
        }

        .stat-card {
            padding: 16px 14px;
            gap: 10px;
            border-radius: 16px;
            flex-direction: column;
            align-items: flex-start;
            box-shadow: 0 4px 12px rgba(255, 129, 10, 0.08);
            border-left: none;
            /* border-top: 3px solid transparent; */
            /* border-image: linear-gradient(90deg, #ff810a, #ffb347) 1; */
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
        }

        .stat-icon i {
            font-size: 1.4rem;
        }

        .stat-info {
            width: 100%;
        }

        .stat-label {
            font-size: 0.75rem;
            margin-bottom: 6px;
            font-weight: 900;
            color: #777;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 600;
        }

        /* App-like Rewards Sections */
        .rewards-sections-container {
            grid-template-columns: 1fr !important;
            gap: 12px;
            padding: 0 15px 15px 15px;
        }

        .rewards-section,
        .referral-history.rewards-section {
            padding: 18px !important;
            min-height: auto !important;
            width: 100% !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 15px rgba(255, 129, 10, 0.08) !important;
        }

        .rewards-section h3,
        .referral-history h3 {
            font-size: 1.05rem !important;
            margin-bottom: 15px !important;
            font-weight: 700 !important;
        }

        .rewards-section h3 i,
        .referral-history h3 i {
            font-size: 1.2rem !important;
        }

        .rewards-section small {
            font-size: 0.7rem !important;
        }

        .empty-state {
            padding: 35px 20px !important;
        }

        .empty-state i {
            font-size: 2.8rem !important;
            margin-bottom: 12px !important;
        }

        .empty-state p {
            font-size: 0.88rem !important;
            color: #888 !important;
            line-height: 1.5 !important;
        }

        .reward-item {
            padding: 16px;
            margin-bottom: 12px;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .reward-item h5 {
            font-size: 1rem;
            font-weight: 700;
        }

        .reward-badge {
            padding: 5px 10px;
            font-size: 0.65rem;
        }

        .claim-btn {
            padding: 12px 20px;
            font-size: 0.85rem;
            width: 100%;
            border-radius: 12px;
            font-weight: 700;
        }

        /* Referral code group */
        .referral-code-group {
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }

        .referral-code-group h6 {
            font-size: 1rem;
            font-weight: 700;
        }

        .history-item {
            padding: 12px 0;
            font-size: 0.9rem;
        }

        /* Add bottom safe area for app-like feel */
        .rewards-sections-container {
            padding-bottom: 20px !important;
        }
    }

    /* Small phones - Enhanced App Experience */
    @media (max-width: 480px) {
        .referral-container::before {
            height: 42vh;
            border-radius: 0 0 25px 25px;
        }

        .referral-header {
            padding: 25px 15px 20px 15px;
        }

        .referral-card h1 {
            font-size: 1.25rem;
            margin-bottom: 16px;
            margin-top: 8px;
            font-weight: 800;
        }

        .referral-header img {
            height: 140px !important;
            border-radius: 12px !important;
        }

        .process-steps {
            gap: 8px;
            padding: 18px 12px;
        }

        .step-item {
            max-width: 85px;
        }

        .step-icon {
            width: 44px;
            height: 44px;
        }

        .step-icon i {
            font-size: 1.1rem;
        }

        .step-item p {
            font-size: 0.68rem;
            line-height: 1.25;
        }

        .referral-code-section {
            padding: 20px 12px;
            margin: 12px;
        }

        .referral-label {
            font-size: 0.68rem;
        }

        .referral-code {
            font-size: 1.4rem;
            padding: 16px 28px;
            letter-spacing: 2.5px;
            border-radius: 14px;
        }

        .tap-to-copy {
            padding: 12px 28px;
            font-size: 0.8rem;
            border-radius: 25px;
        }

        /* Stats in single column for small phones */
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 10px;
            padding: 0 12px;
        }

        .stat-card {
            padding: 14px 16px;
            flex-direction: row;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
        }

        .stat-icon i {
            font-size: 1.5rem;
        }

        .stat-info {
            flex: 1;
        }

        .stat-label {
            font-size: 0.78rem;
            margin-bottom: 4px;
        }

        .stat-number {
            font-size: 1.6rem;
        }

        /* Rewards sections */
        .rewards-sections-container {
            padding: 0 12px 20px 12px;
            gap: 10px;
        }

        .rewards-section,
        .referral-history.rewards-section {
            padding: 16px !important;
            border-radius: 14px !important;
        }

        .rewards-section h3,
        .referral-history h3 {
            font-size: 1rem !important;
        }

        .rewards-section h3 i,
        .referral-history h3 i {
            font-size: 1.15rem !important;
        }

        .rewards-section small {
            font-size: 0.68rem !important;
        }

        .reward-item {
            padding: 14px;
            margin-bottom: 10px;
        }

        .reward-item h5 {
            font-size: 0.95rem;
            padding-right: 70px;
        }

        .reward-item p {
            font-size: 0.8rem;
        }

        .reward-badge {
            padding: 4px 8px;
            font-size: 0.6rem;
        }

        .claim-btn {
            padding: 10px 18px;
            font-size: 0.8rem;
            border-radius: 10px;
        }

        .empty-state {
            padding: 30px 18px !important;
        }

        .empty-state i {
            font-size: 2.5rem !important;
        }

        .empty-state p {
            font-size: 0.85rem !important;
        }

        .referral-code-group {
            padding: 14px;
            border-radius: 12px;
        }

        .referral-code-group h6 {
            font-size: 0.95rem;
        }

        .history-item {
            padding: 10px 0;
            font-size: 0.85rem;
        }

        /* Progress bars */
        .progress {
            height: 6px;
        }

        .progress-bar-wrapper {
            margin: 12px 0;
        }

        /* Add subtle animations for app feel */
        .stat-card,
        .reward-item,
        .rewards-section,
        .referral-code-section {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:active,
        .reward-item:active {
            transform: scale(0.98);
        }
    }
</style>
@endpush

@section('containt')
<div class="referral-container">
    <!-- Main Referral Card -->
    <div class="referral-card">
        <!-- Top Section with MY EARNINGS -->
        <div class="earnings-header">
            <span class="earnings-label">MY EARNINGS</span>
        </div>

        <div class="referral-header">
            <h1>Invite Your Friend And Earn Money</h1>
            <div>
                {{-- <div class="friend-avatar male">👨</div>
                <div class="friend-avatar female">👩</div>
                <div class="money-coins">
                    <span class="coin">💰</span>
                    <span class="coin">💰</span>
                </div> --}}
                <img src="https://img.freepik.com/premium-vector/refer-friend-concept-people-share-info-about-referral-earn-money_566886-9539.jpg"
                style="height: 190px;border-radius:20px;" alt="">
            </div>
        </div>

        {{-- <div class="referral-description">
            <p>Share your referral link and invite your friends via SMS / Email / Whatsapp. You can earn upto ₹100000</p>
        </div> --}}

        <!-- Process Steps with Icons -->
        <div class="process-steps">
            <div class="step-item">
                <div class="step-circle">
                    <div class="step-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <p>Invite your friends to join</p>
            </div>
            <div class="step-item">
                <div class="step-circle">
                    <div class="step-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                </div>
                <p>Your friends get a product discount</p>
            </div>
            <div class="step-item">
                <div class="step-circle">
                    <div class="step-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <p>You and your friends get amount</p>
            </div>
        </div>

        @if($stats['referral_code'])
            <div class="referral-code-section">
                <label class="referral-label">YOUR REFERRAL CODE</label>
                <div class="referral-code-container">
                    <div class="code-display-box">
                        <div class="referral-code" id="referralCode">{{ $stats['referral_code'] }}</div>
                    </div>
                    <div class="tap-to-copy" onclick="copyReferralCode()">TAP TO COPY</div>
                </div>
                <div class="code-success-message" id="copySuccess" style="display: none;">
                    <i class="fas fa-check-circle"></i> Code copied successfully!
                </div>
            </div>
        @else
            <button class="btn btn-light btn-lg" onclick="generateReferralCode()">
                <i class="fas fa-plus"></i> Generate My Referral Code
            </button>
        @endif
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Referrals</span>
                <div class="stat-number">{{ $stats['total_referrals'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Your Orders</span>
                <div class="stat-number">{{ $stats['successful_orders'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-lock-open"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Unlocked</span>
                <div class="stat-number">{{ $stats['user_rewards']['unlocked'] + $stats['sponsor_rewards']['unlocked'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Claimed</span>
                <div class="stat-number">{{ $stats['user_rewards']['claimed'] + $stats['sponsor_rewards']['claimed'] }}</div>
            </div>
        </div>
    </div>

    <!-- User Rewards Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-gift"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Beneficiary Rewards</span>
                <div class="stat-number">{{ $stats['user_rewards']['total'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-unlock-alt"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Unlocked (User)</span>
                <div class="stat-number">{{ $stats['user_rewards']['unlocked'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Sponsor Rewards</span>
                <div class="stat-number">{{ $stats['sponsor_rewards']['total'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Unlocked (Sponsor)</span>
                <div class="stat-number">{{ $stats['sponsor_rewards']['unlocked'] }}</div>
            </div>
        </div>
    </div>

    <!-- Rewards and History Sections -->
    <div class="rewards-sections-container">
        <!-- User Rewards (As Beneficiary) -->
        <div class="rewards-section">
            <h3 class="mb-4">
                <i class="fas fa-gift"></i>
                <div>
                     Your Rewards as Beneficiary
                    <small class="text-muted">(Rewards you earn when using referral codes)</small>
                </div>
            </h3>

            <div id="user-rewards-container">
                <!-- User rewards will be loaded here -->
            </div>
        </div>

        <!-- Sponsor Rewards (As Sponsor) -->
        <div class="rewards-section">
            <h3 class="mb-4">
                <i class="fas fa-trophy"></i>
                <div>
                     Your Rewards as Sponsor
                    <small class="text-muted">(Rewards you earn when others use your referral code)</small>
                </div>
            </h3>

            <div id="sponsor-rewards-container">
                <!-- Sponsor rewards will be loaded here -->
            </div>
        </div>

        <!-- Referral History -->
        <div class="referral-history rewards-section">
            <h3 class="mb-4">
                <i class="fas fa-history"></i>
                <div>
                    Referral History
                </div>
            </h3>

            <div id="history-container">
                <!-- History will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Hidden data for sharing -->
<div id="shareData" style="display: none;" data-code="{{ $stats['referral_code'] ?? '' }}"></div>
@endsection

@push('javascript')

<script>
let shareData = {};

document.addEventListener('DOMContentLoaded', function() {
    loadRewards();
    loadHistory();
    loadShareData();
});

function loadShareData() {
    fetch('{{ route("user.referral.share-info") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                shareData = data.share_data;
            }
        })
        .catch(error => console.error('Error loading share data:', error));
}

function loadRewards() {
    fetch('{{ route("user.referral.rewards") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayUserRewards(data.user_rewards);
                displaySponsorRewards(data.sponsor_rewards);
            }
        })
        .catch(error => console.error('Error loading rewards:', error));
}

function loadHistory() {
    fetch('{{ route("user.referral.history") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayHistory(data);
            }
        })
        .catch(error => console.error('Error loading history:', error));
}

function displayUserRewards(rewards) {
    const container = document.getElementById('user-rewards-container');

    if (rewards.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-gift"></i>
                <p>No rewards available yet.<br>Start referring friends to unlock rewards!</p>
            </div>
        `;
        return;
    }

    container.innerHTML = rewards.map(reward => {
        const progress = Math.min((reward.current_orders / reward.order_limit) * 100, 100);
        const statusClass = reward.is_claimed ? 'claimed' : (reward.is_unlocked ? 'unlocked' : 'pending');
        const statusText = reward.is_claimed ? 'Claimed' : (reward.is_unlocked ? 'Available' : 'Pending');

        let rewardText = '';
        if (reward.reward_type === 'cashback') {
            rewardText = `₹${reward.reward_value} Cashback`;
        } else {
            rewardText = reward.discount_type === 'percentage'
                ? `${reward.reward_value}% Discount${reward.max_amount ? ` (Max ₹${reward.max_amount})` : ''}`
                : `₹${reward.reward_value} Discount`;
        }

        return `
            <div class="reward-item ${statusClass}">
                <div class="reward-badge ${statusClass}">${statusText}</div>
                <h5>${rewardText}</h5>
                <p class="text-muted mb-2">
                    <i class="fas fa-user text-success"></i> Beneficiary • ${reward.order_limit} orders needed
                    ${reward.sponsor ? `<br><small>From: ${reward.sponsor.full_name || reward.sponsor.name}</small>` : ''}
                </p>
                <div class="progress-bar-wrapper">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Progress: ${reward.current_orders}/${reward.order_limit}</small>
                        <small>${Math.round(progress)}%</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: ${progress}%"></div>
                    </div>
                </div>
                ${reward.is_unlocked && !reward.is_claimed ?
                    `<button class="claim-btn" onclick="claimUserReward(${reward.id})">
                        <i class="fas fa-gift"></i> Claim Reward
                    </button>` : ''
                }
            </div>
        `;
    }).join('');
}

function displaySponsorRewards(rewards) {
    const container = document.getElementById('sponsor-rewards-container');

    if (rewards.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-trophy"></i>
                <p>No sponsor rewards yet.<br>Share your referral code to start earning!</p>
            </div>
        `;
        return;
    }

    container.innerHTML = rewards.map(reward => {
        const progress = Math.min((reward.current_orders / reward.order_limit) * 100, 100);
        const statusClass = reward.is_claimed ? 'claimed' : (reward.is_unlocked ? 'unlocked' : 'pending');
        const statusText = reward.is_claimed ? 'Claimed' : (reward.is_unlocked ? 'Available' : 'Pending');

        let rewardText = '';
        if (reward.reward_type === 'cashback') {
            rewardText = `₹${reward.reward_value} Cashback`;
        } else {
            rewardText = reward.discount_type === 'percentage'
                ? `${reward.reward_value}% Discount${reward.max_amount ? ` (Max ₹${reward.max_amount})` : ''}`
                : `₹${reward.reward_value} Discount`;
        }

        return `
            <div class="reward-item ${statusClass}">
                <div class="reward-badge ${statusClass}">${statusText}</div>
                <h5>${rewardText}</h5>
                <p class="text-muted mb-2">
                    <i class="fas fa-crown text-warning"></i> Sponsor Reward • ${reward.order_limit} orders needed
                    ${reward.beneficiary ? `<br><small>From referral: ${reward.beneficiary.full_name || reward.beneficiary.name}</small>` : ''}
                </p>
                <div class="progress-bar-wrapper">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Progress: ${reward.current_orders}/${reward.order_limit} orders</small>
                        <small>${Math.round(progress)}%</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: ${progress}%"></div>
                    </div>
                </div>
                ${reward.is_unlocked && !reward.is_claimed ?
                    `<button class="claim-btn mt-2" onclick="claimSponsorReward(${reward.id})" style="background: #ffc107; border-color: #ffc107;">
                        <i class="fas fa-crown"></i> Apply Sponsor Reward
                    </button>` : ''
                }
            </div>
        `;
    }).join('');
}

function displayHistory(data) {
    const container = document.getElementById('history-container');

    if (!data.referral_history || data.referral_history.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>No referrals yet.<br>Share your code to start earning!</p>
            </div>
        `;
        return;
    }

    // Group by referral code for better display
    const groupedHistory = {};
    data.referral_history.forEach(item => {
        if (!groupedHistory[item.referral_code]) {
            groupedHistory[item.referral_code] = {
                code: item.referral_code,
                total_uses: item.total_code_uses,
                created_at: item.created_at,
                uses: []
            };
        }
        groupedHistory[item.referral_code].uses.push(item);
    });

    container.innerHTML = Object.values(groupedHistory).map(group => {
        const codeDate = new Date(group.created_at).toLocaleDateString();

        return `
            <div class="referral-code-group">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">
                        <i class="fas fa-qrcode text-primary"></i>
                        ${group.code}
                    </h6>
                    <small class="text-muted">
                        ${group.total_uses} uses • ${codeDate}
                    </small>
                </div>
                <div class="uses-list">
                    ${group.uses.map(use => {
                        const useDate = new Date(use.used_at).toLocaleDateString();
                        const userName = use.beneficiary.f_name + ' ' + use.beneficiary.l_name;

                        return `
                            <div class="history-item border-start border-success ps-2">
                                <div>
                                    <strong>${userName}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-user-check text-success"></i>
                                        Joined ${useDate}
                                    </small>
                                </div>
                                <div>
                                    <span class="badge bg-success">Active</span>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        `;
    }).join('');
}

function generateReferralCode() {
    fetch('{{ route("user.referral.generate") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to generate referral code');
        }
    })
    .catch(error => {
        console.error('Error generating code:', error);
        alert('Failed to generate referral code');
    });
}

function claimUserReward(rewardId) {
    fetch('{{ route("user.referral.claim-user") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reward_id: rewardId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User reward claimed successfully!');
            loadRewards();
        } else {
            alert(data.message || 'Failed to claim user reward');
        }
    })
    .catch(error => {
        console.error('Error claiming user reward:', error);
        alert('Failed to claim user reward');
    });
}

function claimSponsorReward(rewardId) {
    fetch('{{ route("user.referral.claim-sponsor") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reward_id: rewardId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Sponsor reward claimed successfully!');
            loadRewards();
        } else {
            alert(data.message || 'Failed to claim sponsor reward');
        }
    })
    .catch(error => {
        console.error('Error claiming sponsor reward:', error);
        alert('Failed to claim sponsor reward');
    });
}

function copyReferralCode() {
    const code = document.getElementById('referralCode').textContent;
    const successMessage = document.getElementById('copySuccess');

    navigator.clipboard.writeText(code).then(() => {
        // Show success message with animation
        successMessage.style.display = 'flex';
        successMessage.style.animation = 'fadeInOut 3s ease-in-out';

        // Hide message after animation
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 3000);
    }).catch(error => {
        console.error('Failed to copy:', error);
        alert('Failed to copy referral code');
    });
}

function copyShareLink() {
    if (shareData.share_url) {
        navigator.clipboard.writeText(shareData.share_url).then(() => {
            alert('Share link copied to clipboard!');
        });
    }
}

function shareWhatsApp() {
    if (shareData.share_text) {
        const url = `https://wa.me/?text=${encodeURIComponent(shareData.share_text)}`;
        window.open(url, '_blank');
    }
}

function shareTwitter() {
    if (shareData.share_text) {
        const url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(shareData.share_text)}`;
        window.open(url, '_blank');
    }
}

function shareFacebook() {
    if (shareData.share_url) {
        const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareData.share_url)}`;
        window.open(url, '_blank');
    }
}
</script>
@endpush
