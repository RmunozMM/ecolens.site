<?php
$ch = curl_init("https://ecolens.site/panel-admin/web/api/contenido?api_key=ABCabc123");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$out = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo "❌ Error cURL: $err";
} else {
    echo "✅ Respuesta OK (primeros 200 caracteres):<br>";
    echo substr($out, 0, 200);
}
