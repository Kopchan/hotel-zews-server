<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Photo extends Model
{
    use HasFactory;

    // Заполняемые поля
    protected $fillable = [
        'name',
    ];

    public static function LoadArray($files, $roomId = null, $newsId = null) {
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $fileExt  = $file->extension();

            // Валидация файла
            $validator = Validator::make(['file' => $file], [
                'file' => 'mimes:png,jpeg,webp,avif',
            ]);
            if ($validator->fails()) {
                // Сохранение плохого ответа API
                $response['errors'][] = [
                    'name'    => $fileName,
                    'message' => 'Photo not in png, jpeg, webp or avif format',
                ];
                continue;
            }
            $fileHash = md5(File::get($file->getRealPath()));
            $photoName = "$fileHash.$fileExt";
            $photo = Photo::firstOrCreate(['name' => $photoName]);

            if ($roomId) $photo->room_id = $roomId;
            if ($newsId) $photo->news_id = $newsId;
            $photo->save();

            // Сохранение файла в хранилище
            if (!Storage::exists('public'.$photoName))
                $file->storeAs  ('public',$photoName);
        }
        return $response ?? [];
    }

    // Связи
    public function room() {
        return $this->belongsTo(Room::class);
    }
    public function news() {
        return $this->belongsTo(News::class);
    }
    public function services() {
        return $this->hasMany(Service::class);
    }
}
