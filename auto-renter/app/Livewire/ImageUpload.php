<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ImageUpload extends Component
{
    use WithFileUploads;
    public $image;

    public function uploadImage()
    {

        $this->validate([
            'image' => 'image|max:1024', // 1MB Max
        ]);

        $image_name = time() . '-' . $this->image->getClientOriginalName();

        $res = $this->image->storeAs('images', $image_name, 'custom_public_path');
        $img_path = asset('uploads/images/' . $image_name);

        return back()->with('success_msg', 'image uploaded')
            ->with('uploaded_img', $img_path);
    }

    public function render()
    {
        return view('livewire.image-upload');
    }
}
