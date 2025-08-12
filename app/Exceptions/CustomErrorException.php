<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class CustomErrorException extends \Exception
{
    protected $statusCode;
    protected $translationKey;
    protected $translationGroup;
    protected array $translationParams;

    public function __construct($translationKey = '', $translationGroup = 'errorMessage', $statusCode = Response::HTTP_BAD_REQUEST, $translationParams = [])
    {
        parent::__construct($translationKey);
        $this->statusCode = $statusCode;
        $this->translationKey = $translationKey;
        $this->translationGroup = $translationGroup;
        $this->translationParams = $translationParams;
    }

    public function render()
    {
        $locale = App::getLocale();
        App::setLocale($locale);

        $message = __($this->translationGroup . '.' . $this->getMessage(), $this->translationParams);

        return response()->json([
            'error' => [
                'messages' => [$message],
            ]
        ], $this->statusCode);
    }
}
