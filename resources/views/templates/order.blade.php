<body>
    <div id="container"
        style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; color: #333;">
        <section id="section">
            <h1 style="font-size: 24px; margin-bottom: 20px;">Order {{ $order->order_id }} summary</h1>
            <p style="margin-bottom: 20px;">Dear <span style="font-weight: bold;">{{ $user->firstname }}</span>,
            </p>
            <p style="margin-bottom: 20px;">
                Terima kasih telah memesan makanan di <span style="font-weight: bold;">{{ env('APP_NAME') }}</span>.
                Kami dengan senang hati mengkonfirmasi pesanan Anda dengan detail sebagai berikut:
            </p>
            <hr style="border-bottom: 1px solid; color: #c4c3c3;">
            <table id="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    @php
                        $discount = 0;
                    @endphp
                    @foreach ($detailOrders as $item)
                        <tr style="border-bottom: 1px solid; color: #c4c3c3;">
                            <td style="padding: 5px; color: black;"><img
                                    src="https://img.freepik.com/free-photo/chicken-wings-barbecue-sweetly-sour-sauce-picnic-summer-menu-tasty-food-top-view-flat-lay_2829-6471.jpg?w=2000"
                                    alt="{{ $item['product'] }}" width="50px" height="50px"></td>
                            <td style="padding: 5px; color: black;">{{ $item['product'] }} <span
                                    style="margin-left: 10px">{{ $item['quantity'] }}</span></td>
                            <td style="padding: 5px; color: black;">
                                Rp{{ $item['unit_price'] * $item['quantity'] }}</td>
                            @php
                                $discount = $discount + $item['discount'];
                            @endphp
                        </tr>
                    @endforeach
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" align="right" style="padding: 5px;">Subtotal:</td>
                        <td style="padding: 5px;">Rp{{ (int) $order->amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" style="padding: 5px;">Discount:</td>
                        <td style="padding: 5px; color: red;" id="discount">
                            -Rp{{ $discount }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid; color: #c4c3c3;">
                        <td colspan="2" align="right" style="padding: 5px; color: black;">Tax:</td>
                        <td style="padding: 5px; color: black;">11%</td>
                    </tr>
                    <tr id="total" style="color: black; font-weight: bold;">
                        <td colspan="2" align="right" style="padding: 5px;">Total:</td>
                        <td style="padding: 5px;" id="total-price">
                            Rp{{ (int) $order->gross_amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" style="padding: 5px;">Metode Pembayaran:</td>
                        <td style="padding: 5px; text-transform: uppercase;">{{ $order->bank }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" style="padding: 5px;"><span
                                style="text-transform: uppercase;">{{ $order->bank }} </span>Virtual Account:
                        </td>
                        <td style="padding: 5px;">{{ $order->va_number }}</td>
                    </tr>
                </tbody>
            </table>
            <hr style="border-bottom: 1px solid; color: #c4c3c3;">
            <div class="info" style="margin-top: 20px;">
                <div style="margin-bottom: 10px;">
                    <p style="margin-bottom: 10px;">Jika Anda memiliki pertanyaan atau perlu bantuan lebih lanjut,
                        jangan ragu untuk menghubungi tim layanan pelanggan kami di <span style="font-weight: bold;"><a
                                href="tel:+62815343433">+62815343433</a></span> atau melalui email ini.</p>
                </div>
                <div>
                    <p style="margin-bottom: 0;">Terima kasih atas kepercayaan Anda pada <span
                            style="font-weight: bold;">{{ env('APP_NAME') }}</span>. Kami berharap Anda menikmati
                        hidangan
                        yang Anda pesan.</p>
                    <p style="margin-top: 0; font-style: italic;">Salam hangat, regards</p>
                </div>
            </div>
        </section>
    </div>
</body>
