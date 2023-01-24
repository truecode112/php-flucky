<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Content::create([
            'key' => 'HOME_PAGE',
            'value' => '<p>Welcome to <span class="colored-text">Fluky!</span></p><p>Fluky is a great way to meet new friends, here we pick someone randomly to help you start the conversation. Chats are anonymous until you reveal your identity.</p><p>Click on the <span class="colored-text">Text</span> or <span class="colored-text">Video</span> button to begin the fun!</p>',
        ]);

        Content::create([
            'key' => 'PRIVACY_POLICY',
            'value' => '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>',
        ]);

        Content::create([
            'key' => 'TERMS_AND_CONDITIONS',
            'value' => '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>',
        ]);
    }
}
