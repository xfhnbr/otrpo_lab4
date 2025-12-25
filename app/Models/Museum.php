<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Museum extends Model
{
    use HasFactory;
	use SoftDeletes;
	
    protected $fillable = [
        'user_id',
        'name_ru',
        'name_original',
        'description',
        'detailed_description',
        'address',
        'working_hours',
        'ticket_price',
        'website_url',
        'image_filename'
    ];

    protected $appends = [
        'image_url',
        'formatted_price',
        'created_at_formatted',
        'updated_at_formatted',
		'deleted_at_formatted',
        'created_at_human',
        'short_description',
        'website_domain'
    ];
	
	protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        
        static::updating(function ($museum) {
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->id !== $museum->user_id && !$user->is_admin) {
                    abort(403, 'Вы не можете редактировать этот музей');
                }
            }
        });

        static::deleting(function ($museum) {
            if (!$museum->isForceDeleting() && auth()->check()) {
                $user = auth()->user();
                if ($user->id !== $museum->user_id && !$user->is_admin) {
                    abort(403, 'Вы не можете удалить этот музей');
                }
            }
        });

        static::forceDeleting(function ($museum) {
            if (auth()->check() && !auth()->user()->is_admin) {
                abort(403, 'Только администратор может полностью удалять музеи');
            }
        });
    }
	
    public function popovers()
    {
        return $this->hasMany(Popover::class)->orderBy('position');
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/museums/' . $this->image_filename);
    }
    
    public function getImagePathAttribute()
    {
        return storage_path('app/public/museums/' . $this->image_filename);
    }

    public function getFormattedPriceAttribute()
    {
        return '€' . number_format($this->ticket_price, 2, '.', ' ');
    }
    
    public function getTicketPriceCleanAttribute()
    {
        return $this->ticket_price == (int)$this->ticket_price 
            ? (int)$this->ticket_price 
            : $this->ticket_price;
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d.m.Y H:i');
    }
    
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at->format('d.m.Y H:i');
    }
    
	public function getDeletedAtFormattedAttribute()
    {
        return $this->deleted_at ? $this->deleted_at->format('d.m.Y H:i') : null;
    }
	
    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getShortDescriptionAttribute()
    {
        return Str::limit($this->description, 150);
    }
    
    public function getAddressOnelineAttribute()
    {
        return str_replace(["\r", "\n"], ', ', $this->address);
    }
    
    public function getDescriptionHtmlAttribute()
    {
        return nl2br(e($this->description));
    }

    public function getWebsiteDomainAttribute()
    {
        if (!$this->website_url) {
            return null;
        }
        
        $parsed = parse_url($this->website_url);
        return $parsed['host'] ?? str_replace(['http://', 'https://', 'www.'], '', $this->website_url);
    }
    
    public function getWebsiteUrlFullAttribute()
    {
        if (!$this->website_url) {
            return null;
        }
        
        $url = $this->website_url;
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        
        return $url;
    }

    
    public function getFormattedDescriptionAttribute()
    {
        if (!$this->detailed_description) {
            return '';
        }
        
        $description = $this->detailed_description;
        
        foreach ($this->popovers as $popover) {
            $replace = sprintf(
                '<span class="text-primary" 
                       data-bs-toggle="popover" 
                       data-bs-trigger="hover" 
                       data-bs-title="%s" 
                       data-bs-content="%s">%s</span>',
                e($popover->title),
                e($popover->content),
                e($popover->target_text)
            );
            
            $description = str_replace($popover->target_text, $replace, $description);
        }
        
        return $description;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}