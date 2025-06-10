<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новый заказ #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .content {
            padding: 30px;
        }
        .order-info {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .order-items {
            margin: 20px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
            background-color: #fafafa;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item-name {
            font-weight: 600;
            color: #2c3e50;
        }
        .item-details {
            color: #7f8c8d;
            font-size: 14px;
        }
        .item-price {
            font-weight: bold;
            color: #27ae60;
            font-size: 16px;
        }
        .total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            border-radius: 8px;
        }
        .client-info {
            background-color: #e8f4fd;
            border: 1px solid #b8daff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-new {
            background-color: #3498db;
            color: white;
        }
        .status-processing {
            background-color: #f39c12;
            color: white;
        }
        .status-error {
            background-color: #e74c3c;
            color: white;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .admin-link {
            display: inline-block;
            background-color: #27ae60;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .admin-link:hover {
            background-color: #219a52;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🛒 Новый заказ #{{ $order->id }}</h1>
            <p>Поступил новый заказ на сайте {{ config('app.name') }}</p>
        </div>

        <div class="content">
            <div class="order-info">
                <h3>📋 Информация о заказе</h3>
                <p><strong>Номер заказа:</strong> {{ $order->id }}</p>
                <p><strong>Дата:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                <p><strong>Статус:</strong> 
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p><strong>GUID:</strong> {{ $order->guid }}</p>
            </div>

            @if($client)
            <div class="client-info">
                <h3>👤 Информация о клиенте</h3>
                <p><strong>Имя:</strong> {{ $client->name ?? 'Не указано' }}</p>
                <p><strong>Email:</strong> {{ $client->email ?? 'Не указано' }}</p>
                <p><strong>Телефон:</strong> {{ $client->phone ?? 'Не указано' }}</p>
                <p><strong>ID клиента:</strong> {{ $client->id }}</p>
            </div>
            @else
            <div class="client-info">
                <h3>👤 Гостевой заказ</h3>
                <p>Заказ был оформлен без регистрации</p>
            </div>
            @endif

            <div class="order-items">
                <h3>🛍️ Товары в заказе</h3>
                @foreach($cart->items as $item)
                    <div class="item">
                        <div>
                            <div class="item-name">{{ $item->product->name }}</div>
                            <div class="item-details">
                                SKU: {{ $item->product->sku ?? 'Не указано' }} | 
                                Количество: {{ $item->qty }} шт.
                            </div>
                        </div>
                        <div class="item-price">
                            {{ number_format($item->unit_price * $item->qty, 2) }} 
                            MDL
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="total">
                💰 Общая сумма: {{ number_format($order->total, 2) }} MDL
            </div>

            @if($order->comments)
            <div class="order-info">
                <h3>💬 Комментарии</h3>
                <p>{{ $order->comments }}</p>
            </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/admin/order-customs/{{ $order->id }}/edit" class="admin-link">
                    🔧 Управлять заказом в админ-панели
                </a>
            </div>
        </div>

        <div class="footer">
            <p>Это автоматическое уведомление с сайта {{ config('app.name') }}</p>
            <p>{{ config('app.url') }}</p>
            <p>{{ now()->format('d.m.Y H:i') }}</p>
        </div>
    </div>
</body>
</html> 