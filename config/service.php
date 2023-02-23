<?php
return [
   "notif" => [
      "url" => env("SERVICE_NOTIF_URL", "http://127.0.0.1"),
      "name" => "SERVICE_NOTIF",
      "sigkey" => env("SERVICE_NOTIF_SIGNATURE_KEY",env("SIGNATURE_KEY","#signature_key")),
      "enchip" => env("SERVICE_NOTIF_ENCRYPTION_CIPHER",env("ENCRYPTION_CIPHER","#signature_key")),
      "encriv" => env("SERVICE_NOTIF_ENCRYPTION_IV",env("ENCRYPTION_IV","#signature_key")),
      "enckey" => env("SERVICE_NOTIF_ENCRYPTION_KEY",env("ENCRYPTION_KEY","#signature_key"))
   ],
   "member" => [
      "url" => env("SERVICE_MEMBER_URL", "http://127.0.0.1"),
      "name" => "SERVICE_MEMBER",
      "sigkey" => env("SERVICE_MEMBER_SIGNATURE_KEY",env("SIGNATURE_KEY","#signature_key")),
      "enchip" => env("SERVICE_MEMBER_ENCRYPTION_CIPHER",env("ENCRYPTION_CIPHER","#signature_key")),
      "encriv" => env("SERVICE_MEMBER_ENCRYPTION_IV",env("ENCRYPTION_IV","#signature_key")),
      "enckey" => env("SERVICE_MEMBER_ENCRYPTION_KEY",env("ENCRYPTION_KEY","#signature_key"))
   ],
   "alika" => [
      "url" => env("SERVICE_ALIKA_URL", "http://127.0.0.1"),
      "name" => "SERVICE_ALIKA",
      "sigkey" => env("SERVICE_ALIKA_SIGNATURE_KEY",env("SIGNATURE_KEY","#signature_key")),
      "enchip" => env("SERVICE_ALIKA_ENCRYPTION_CIPHER",env("ENCRYPTION_CIPHER","#signature_key")),
      "encriv" => env("SERVICE_ALIKA_ENCRYPTION_IV",env("ENCRYPTION_IV","#signature_key")),
      "enckey" => env("SERVICE_ALIKA_ENCRYPTION_KEY",env("ENCRYPTION_KEY","#signature_key"))
   ]
];
