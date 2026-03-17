<?php
// Template Name: System


function create_username_from_email($email) {
    $username = substr($email, 0, strpos($email, '@'));
    $username = strtolower($username);
    $username = preg_replace('/[^a-z0-9._]/', '', $username);

    return $username;
}

// SMTP Config
function phpmailer_custom_confiig( $phpmailer ) {
    $phpmailer->isSMTP();
    // $phpmailer->Host       = 'smtp.office365.com';
    $phpmailer->Host = 'smtp-mail.outlook.com';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = 587;
    // $phpmailer->Username   = 'corp\gpe.dvd';
    $phpmailer->Username   = 'info@eurolamp.ua';
    $phpmailer->Password   = 'DVD688565!@#qwe';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From       = 'info@eurolamp.ua';
    $phpmailer->FromName   = 'Manager Natalie';
       
    // $phpmailer->Host = 'smtp-mail.outlook.com';
    // $phpmailer->SMTPAuth = true;
    // $phpmailer->Port = 587;
    // $phpmailer->SMTPSecure = 'tls'; // Choose 'ssl' for SMTPS on port 465, or 'tls' for SMTP+STARTTLS on port 25 or 587

    // $phpmailer->Username = 'corp\gpe.dvd';
    // $phpmailer->Password = 'DVD688565!@#qwe';

    // // Additional settings…
    // $phpmailer->From = "info@eurolamp.ua";
    // $phpmailer->FromName = "Manager Natalie";
}
// add_action( 'phpmailer_init', 'phpmailer_custom_confiig' );


if ( current_user_can('administrator') ) {
$args = array(
    'status' => 'any',
    'limit' => 10,
    'paged' => $_GET['offset'] ? $_GET['offset'] : 1,
    // 'date_created' => '<=2024-09-16'
);

$orders = wc_get_orders( $args );

// $upload_dir = wp_upload_dir();
// $csv_file   = $upload_dir['basedir'] . '/orders-export1.csv';
// $file_exists = file_exists( $csv_file );
// $file = fopen( $csv_file, 'a' );
// if (!$file_exists) {
// 	$headers = array( 'Order ID', 'Date', 'Status', 'Customer Name', 'Email', 'Total' );
// 	fputcsv( $file, $headers );
// }
// foreach ( $orders as $order ) {
//     $line = array(
//         $order->get_id(),
//         $order->get_date_created()->date('Y-m-d H:i:s'),
//         $order->get_status(),
//         $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
//         $order->get_billing_email(),
//         $order->get_total(),
//     );
//     fputcsv( $file, $line );
// }

// fclose( $file );

// $file_url = $upload_dir['baseurl'] . '/orders-export.csv';
// echo 'CSV export complete. File located at: ' . esc_url( $file_url );
?>
<!-- <a href="<?php echo esc_url( $file_url ) ?>">File</a> -->
<br>
<a id="next-action" href="https://eurolamp.ua/shop/?page_id=10214&preview=true&offset=<?php echo $_GET['offset']+1  ?>">NEXT</a>
<h1>Page: <?php echo $_GET['offset'] ? $_GET['offset'] : 1 ?></h1>
<table>
	<tr>
		<th>ID</th>
		<th>Order ID</th>
		<th>Customer Email</th>
		<th>User ID</th>
		<th>Customer ID</th>
		<th>Name</th>
		<th>Surname</th>
	</tr>
	<?php
	foreach ( $orders as $i => $order ) {
			// if($i < 8) continue;
	    $order_id = $order->get_id();
	    $billing_email = $order->get_billing_email();
	    $customer_id = $order->get_customer_id();

	    $user = get_user_by( 'email', $billing_email );

	    ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $order_id; ?></td>
				<td><?php echo $billing_email; ?></td>
				<td><?php echo $user ? $user->ID : 'NULL'; ?></td>
				<td><?php echo $customer_id; ?></td>
				<td><?php echo $order->get_billing_first_name(); ?></td>
				<td><?php echo $order->get_billing_last_name(); ?></td>
			</tr>
	    <?php

	    if ( $user && !$customer_id ) {
			    $order->set_customer_id( $user->ID );
			    $order->save();
			} elseif (!$user && !$customer_id) {
				$username = create_username_from_email($billing_email);
				$password = wp_generate_password(16);
				$user_id = wp_create_user(
			    $username,
			    $password,
			    $billing_email
			  );
			  $user = new WP_User($user_id);
				$user->set_role('customer');

		    update_user_meta($user_id, 'first_name', $order->get_billing_first_name());
		    update_user_meta($user_id, 'last_name', $order->get_billing_last_name());

			  $order->set_customer_id( $user_id );
			  $order->save();
			} 
	}
	?>
</table>

<!-- <script>
	setTimeout(() => {
		var link = document.getElementById('next-action');

		link.click();
	}, 2000);
</script> -->
<?php

$to = 'denis3ina@gmail.com';
// $to = 'n.gol@eurolamp.ua';
$subject = 'Нові умови знижок – економте ще більше з EUROLAMP!';

$message = '
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Знижки на ваші покупки!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            width: 100%;
            background-color: #ffffff;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header h1 {
            color: #ff8c00;
        }
        .email-body {
            font-size: 16px;
            line-height: 1.5;
        }
        .email-body p {
            margin-bottom: 15px;
        }
        .highlight {
            color: #ff8c00;
            font-weight: bold;
        }
        .discounts ul {
            list-style: none;
            padding: 0;
        }
        .discounts li {
            margin-bottom: 10px;
        }
        .button {
            background-color: #ff8c00;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Вітаємо! 🌟</h1>
        </div>
        <div class="email-body">
            <p>Ми покращили нашу систему знижок, щоб ваші покупки були ще вигіднішими! Відтепер діє накопичувальна система знижок, яка дозволяє економити автоматично при оформленні замовлення.</p>

            <div class="discounts">
                <p><strong>🔹 Як це працює?</strong><br>
                Чим більше ви купуєте, тим більшу знижку отримуєте:</p>
                <ul>
                    <li>✅ Від 2 000 ₴ – знижка 3%</li>
                    <li>✅ Від 4 000 ₴ – знижка 5%</li>
                    <li>✅ Від 6 000 ₴ – знижка 8%</li>
                    <li>✅ Від 10 000 ₴ – знижка 10%</li>
                    <li>✅ Від 15 000 ₴ – максимальна знижка 15%</li>
                </ul>
            </div>

            <p><strong>📌 Приклад:</strong><br>
            Якщо ви вже зробили покупки на 4 500 ₴, то при наступному замовленні ваша знижка складе 5%. Коли загальна сума покупок досягне 10 000 ₴ – ви отримаєте 10% знижки і так далі. Ваш рівень знижки оновлюється автоматично після кожної покупки!</p>

            <p><strong>🎯 Перевірте свою персональну знижку!</strong><br>
            Ми вже завантажили у систему інформацію про ваші попередні покупки. Просто увійдіть у свій акаунт, щоб переглянути історію замовлень та дізнатися про свою поточну знижку.</p>

            <p><strong>🔑 Ваші дані для входу:</strong><br>
            Логін: <span class="highlight">'.$to.'</span><br>
            Пароль: <span class="highlight">'.$password.'</span></p>

            <p><a href="https://eurolamp.ua/shop/" class="button">Перейти на сайт і робити покупки</a></p>
        </div>

        <div class="footer">
            <p>Гарного дня та приємного шопінгу!<br>
            Команда <strong>EUROLAMP 💡</strong></p>
        </div>
    </div>
</body>
</html>
';
$headers = array('Content-Type: text/html; charset=UTF-8');

// $sent = wp_mail( $to, $subject, $message, $headers );

// if ($sent) {
//   echo '✅ Email sent successfully via Outlook SMTP.';
// } else {
//   echo '❌ Email failed to send.';
// }

} else {
  status_header( 404 );
  get_template_part( '404' );
}
?>