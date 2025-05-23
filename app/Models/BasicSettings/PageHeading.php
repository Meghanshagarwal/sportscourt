<?php

namespace App\Models\BasicSettings;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHeading extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'hotel_page_title',
    'hotel_details_page_title',
    'rooms_page_title',
    'room_checkout_page_title',
    'blog_page_title',
    'contact_page_title',
    'products_page_title',
    'product_details_page_title',
    'faq_page_title',
    'forget_password_page_title',
    'vendor_forget_password_page_title',
    'pricing_page_title',
    'login_page_title',
    'signup_page_title',
    'vendor_login_page_title',
    'vendor_signup_page_title',
    'error_page_title',
    'cart_page_title',
    'checkout_page_title',
    'vendor_page_title',
    'about_us_title',
    'dashboard_page_title',
    'room_wishlist_page_title',
    'hotel_wishlist_page_title',
    'room_bookings_page_title',
    'room_booking_details_page_title',
    'support_ticket_page_title',
    'support_ticket_create_page_title',
    'change_password_page_title',
    'edit_profile_page_title',
    'custom_page_heading'
  ];

  public function headingLang()
  {
    return $this->belongsTo(Language::class);
  }
}
