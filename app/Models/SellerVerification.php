<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'store_name', 
        'store_description', 
        'id_type',      
        'id_number', 
        'document_path', 
        'video_path',    
        'video_intro_path', 
        'logo_path', 
        'business_permit_path',
        'nbi_clearance_path',   
        'plan',        
        'badge_level',   
        'status',        
        'rejection_reason', 
        'verified_at',
        'wet_market_logo',
        'wet_market_banner_paths',
        'sari_sari_logo',
        'sari_sari_banner_paths',
        'webstore_promotional_text',
        'webstore_established_year',
        'webstore_store_hours',
        'webstore_contact_representatives',
        'webstore_certificates_data',
        'wet_market_promotional_text',
        'wet_market_established_year',
        'wet_market_store_hours',
        'wet_market_contact_representatives',
        'wet_market_certificates_data',
        'sari_sari_promotional_text',
        'sari_sari_established_year',
        'sari_sari_store_hours',
        'sari_sari_contact_representatives',
        'sari_sari_certificates_data',
    ];  

    /**
     * Get the user that owns the verification request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to check if this is an "Oppa Badge" holder.
     * Trusted sellers have the highest security clearance[cite: 1].
     */
    public function isTrusted()
    {
        return $this->badge_level === 'Trusted' && $this->status === 'approved';
    }

  protected $casts = [
    'banner_slider_paths' => 'array',
    'wet_market_banner_paths' => 'array',
    'sari_sari_banner_paths' => 'array',

    'webstore_contact_representatives' => 'array',
    'webstore_certificates_data' => 'array',

    'wet_market_contact_representatives' => 'array',
    'wet_market_certificates_data' => 'array',

    'sari_sari_contact_representatives' => 'array',
    'sari_sari_certificates_data' => 'array',
];
}