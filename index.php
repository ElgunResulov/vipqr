<?php

declare(strict_types=1);

// Fallback if rewrite to public/ is unavailable
header('Location: public/', true, 302);
exit;
