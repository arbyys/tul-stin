<?php

namespace Tests\Helpers;

use PragmaRX\Google2FA\Google2FA;

class FakeGoogle2FA extends Google2FA
{
    public function verifyGoogle2FA()
    {
        return true;
    }
}
