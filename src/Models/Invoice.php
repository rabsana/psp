<?php

namespace Rabsana\Psp\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;
use Rabsana\Core\Support\Facades\ConvertDate;
use Rabsana\Core\Support\Facades\Math;

class Invoice extends Model
{
    // Use modules, traits, plugins ...


    // statuses
    const CREATED   = 'CREATED';
    const PAID      = 'PAID';
    const CANCELED  = 'CANCELED';
    const EXPIRED   = 'EXPIRED';
    const FAILED    = 'FAILED';



    // Config the model
    protected $guarded = ['id'];


    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('rabsana-psp.database.invoices.table', 'invoices');
    }

    // Filters

    public function scopeSearch($query, $request)
    {
        // serach merchant name
        if ($request->has('merchant')) {
            $query = $query->whereHas('merchant', function($query) use($request) {
                $query->where('name', 'like', "%$request->merchant%");
            });
        }

        // serach user name
        if ($request->has('user')) {
            $query = $query->whereHas('user', function($query) use($request) {
                $query->where('name', 'like', "%$request->user%");
            });
        }

        // serach user currency
        if ($request->has('currency')) {
            $query = $query->whereHas('currency', function($query) use($request) {
                $query->where('name', 'like', "%$request->currency%")->orWhere('abbreviation', $request->currency);
            });
        }

        // search from date
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query = $query->where('created_at', '>', $this->getMiladi($request, 'from_date'));
        }

        // search to date
        if ($request->has('to_date') && !empty($request->to_date)) {
            $query = $query->where('created_at', '<', $this->getMiladi($request, 'to_date'));
        }


        return $query;
    }

    public function scopeUserId($query, $userId = null)
    {
        if (filled($userId)) {
            $query->where('user_id', $userId);
        }
        return $query;
    }

    public function scopeMerchantId($query, $merchantId = null)
    {
        if (filled($merchantId)) {
            $query->where('merchant_id', $merchantId);
        }
        return $query;
    }

    public function scopeCurrencyId($query, $currencyId = null)
    {
        if (filled($currencyId)) {
            $query->where('currency_id', $currencyId);
        }
        return $query;
    }

    public function scopeToken($query, $token = null)
    {
        if (filled($token)) {
            $query->where('token', $token);
        }
        return $query;
    }

    public function scopeStatus($query, $status = null)
    {
        if (filled($status)) {
            $query->whereRaw('UPPER(status) = ?', [strtoupper($status)]);
        }
        return $query;
    }

    public function scopeBase($query, $base = null)
    {
        if (filled($base)) {
            $query->whereRaw('UPPER(base) = ?', [strtoupper($base)]);
        }
        return $query;
    }

    public function scopeQuote($query, $quote = null)
    {
        if (filled($quote)) {
            $query->whereRaw('UPPER(quote) = ?', [strtoupper($quote)]);
        }
        return $query;
    }

    // Relations

    public function user()
    {
        return $this->belongsTo(config('rabsana-psp.modelRelations.user', 'App\\Model\\User'));
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function currency()
    {
        return $this->belongsTo(config('rabsana-psp.modelRelations.currency', 'App\\Model\\Currency'));
    }

    // Accessors
    public function getBaseAttribute($base)
    {
        return strtoupper($base);
    }

    public function getQuoteAttribute($quote)
    {
        return strtoupper($quote);
    }

    public function getQtyPrettifiedAttribute()
    {
        return Math::numberFormat((float) $this->qty);
    }

    public function getAmountPrettifiedAttribute()
    {
        return Math::numberFormat((float) $this->amount);
    }

    public function getStatusInfoAttribute()
    {
        return (array) collect($this->getStatuses())->where('name', $this->status)->first();
    }

    public function getIsExpiredAttribute()
    {
        return $this->status == self::EXPIRED;
    }

    public function getIsPayableAttribute()
    {
        return in_array($this->status, [
            self::CREATED
        ]);
    }

    public function getJcreatedAtAttribute()
    {
        return ConvertDate::gtoj($this->created_at);
    }


    // Mutators
    public function setBaseAttribute($base)
    {
        $this->attributes['base'] = strtoupper($base);
    }

    public function setQuoteAttribute($quote)
    {
        $this->attributes['quote'] = strtoupper($quote);
    }

    // Extra methods

    public function getStatuses(): array
    {
        return [
            [
                'name'              => self::CREATED,
                'color'             => 'warning',
                'name_translated'   => 'ایجاد شده'
            ],
            [
                'name'              => self::PAID,
                'color'             => 'success',
                'name_translated'   => 'پرداخت شده'
            ],
            [
                'name'              => self::CANCELED,
                'color'             => 'dark',
                'name_translated'   => 'لغو شده'
            ],
            [
                'name'              => self::EXPIRED,
                'color'             => 'info',
                'name_translated'   => 'منقضی شده'
            ],
            [
                'name'              => self::FAILED,
                'color'             => 'danger',
                'name_translated'   => 'ناموفق'
            ],
        ];
    }

    public static function getInvoiceStatuses(): array
    {
        return [
            [
                'name'              => self::CREATED,
                'color'             => 'warning',
                'name_translated'   => 'ایجاد شده'
            ],
            [
                'name'              => self::PAID,
                'color'             => 'success',
                'name_translated'   => 'پرداخت شده'
            ],
            [
                'name'              => self::CANCELED,
                'color'             => 'dark',
                'name_translated'   => 'لغو شده'
            ],
            [
                'name'              => self::EXPIRED,
                'color'             => 'info',
                'name_translated'   => 'منقضی شده'
            ],
            [
                'name'              => self::FAILED,
                'color'             => 'danger',
                'name_translated'   => 'ناموفق'
            ],
        ];
    }

    private function getMiladi($request, $name)
    {
        $date = null;
        if ($request->has($name) && !empty($request->get($name))) {
            $date =  CalendarUtils::createDatetimeFromFormat('Y-m-d', $request->{$name})->format('Y-m-d');
        }
        return $date;
    }
}
