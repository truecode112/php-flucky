<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GlobalConfig;

class GlobalConfigSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    GlobalConfig::create([
      'key' => 'APPLICATION_NAME',
      'value' => 'Fluky',
      'description' => 'Application Name will be visible in the entire application.',
    ]);

    GlobalConfig::create([
      'key' => 'THEME_COLOR',
      'value' => '#9a39ba',
      'description' => 'Set the theme color for the front-end.',
    ]);

    GlobalConfig::create([
      'key' => 'DEFAULT_THEME',
      'value' => 'light',
      'description' => 'Set a default theme.',
    ]);

    GlobalConfig::create([
      'key' => 'LOGO',
      'value' => 'LOGO.png',
      'description' => 'This will be the application logo (155x35 PNG). The maximum allowed size is 2 MB.',
    ]);

    GlobalConfig::create([
      'key' => 'FAVICON',
      'value' => 'FAVICON.png',
      'description' => 'This will be the favicon. Only PNG is supported. The maximum allowed size is 2 MB.',
    ]);

    GlobalConfig::create([
      'key' => 'SIGNALING_URL',
      'value' => 'https://yourdomain.in:9007',
      'description' => 'Signaling server (NodeJS) URL.',
    ]);

    GlobalConfig::create([
      'key' => 'STUN_URL',
      'value' => 'stun:stun.l.google.com:19302',
      'description' => 'STUN URL for WebRTC. No need to update.',
    ]);

    GlobalConfig::create([
      'key' => 'TURN_URL',
      'value' => 'turn:yourdomain.in',
      'description' => 'TURN URL for WebRTC. Add your server\'s TURN URL once you finish installing it.',
    ]);

    GlobalConfig::create([
      'key' => 'TURN_USERNAME',
      'value' => 'username',
      'description' => 'Enter TURN username (NOT server\'s username).',
    ]);

    GlobalConfig::create([
      'key' => 'TURN_PASSWORD',
      'value' => 'password',
      'description' => 'Enter TURN password (NOT server\'s passsword)',
    ]);

    GlobalConfig::create([
      'key' => 'DEFAULT_USERNAME',
      'value' => 'Stranger',
      'description' => 'This will be the default username when the guest user joins the chat.',
    ]);

    GlobalConfig::create([
      'key' => 'MINIMUM_AGE',
      'value' => '18',
      'description' => 'It will be visible as age warning in the home page.',
    ]);

    GlobalConfig::create([
      'key' => 'COOKIE_CONSENT',
      'value' => 'enabled',
      'description' => 'If enabled, the system will display a cookie consent popup to the visitors.',
    ]);

    GlobalConfig::create([
      'key' => 'GOOGLE_ANALYTICS_ID',
      'value' => 'null',
      'description' => 'Google Analytics tracking ID. Set null to disable. It uses the format G-XXXXXXX.',
    ]);

    GlobalConfig::create([
      'key' => 'LIVE_COUNT_PREFIX',
      'value' => 'null',
      'description' => 'Online users count as a fake prefix. Set null to disable. If it is set to 5 and the actual number is 123, it will be shown as 5123.',
    ]);

    GlobalConfig::create([
      'key' => 'FAKE_VIDEO_TIME',
      'value' => '3',
      'description' => 'After how many seconds of inactivity the fake video should be played.',
    ]);

    GlobalConfig::create([
      'key' => 'FAKE_VIDEO_FREQUENCY',
      'value' => '5',
      'description' => 'After how many users fake video should be played.',
    ]);

    GlobalConfig::create([
      'key' => 'SOCIAL_INVITATION',
      'value' => 'Hey, check out this amazing website, where you can chat with strangers!',
      'description' => 'Social invitation link message.',
    ]);

    GlobalConfig::create([
      'key' => 'PRICING_PLAN_NAME_FREE',
      'value' => 'Basic',
      'description' => 'Pricing title for the free plan.',
    ]);

    GlobalConfig::create([
      'key' => 'PRICING_PLAN_NAME_PAID',
      'value' => 'Premium',
      'description' => 'Pricing title for the paid plan.',
    ]);

    GlobalConfig::create([
      'key' => 'MONTHLY_PRICE',
      'value' => '25',
      'description' => 'Monthly price.',
    ]);

    GlobalConfig::create([
      'key' => 'YEARLY_PRICE',
      'value' => '120',
      'description' => 'Yearly price.',
    ]);

    GlobalConfig::create([
      'key' => 'STRIPE_KEY',
      'value' => 'pk_test_example',
      'description' => 'Stripe payment gateway key. You can get it from your Stripe dashboard.',
    ]);

    GlobalConfig::create([
      'key' => 'STRIPE_SECRET',
      'value' => 'sk_test_example',
      'description' => 'Stripe payment gateway secret. You can get it from your Stripe dashboard.',
    ]);

    GlobalConfig::create([
      'key' => 'CURRENCY',
      'value' => 'USD',
      'description' => 'Currency to accept payment.',
    ]);

    GlobalConfig::create([
      'key' => 'AUTH_MODE',
      'value' => 'enabled',
      'description' => 'This mode will enable register, dashboard, profile, etc modules. If this mode is disabled use \'login\' URL manually to login.',
    ]);

    GlobalConfig::create([
      'key' => 'PAYMENT_MODE',
      'value' => 'disabled',
      'description' => 'This mode will enable the payment module. An extended license is required.',
    ]);

    GlobalConfig::create([
      'key' => 'VERSION',
      'value' => '2.2.1',
      'description' => 'Current version.',
    ]);
  }
}
