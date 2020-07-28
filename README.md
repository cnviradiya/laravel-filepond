# laravel-filepond
Laravel module for the filepond

### Setup process
```composer require cnviradiya/laravel-filepond```

#### Publish configuration file for the package
```php artisan vendor:publish --provider="Cnviradiya\LaravelFilepond\LaravelFilepondServiceProvider"```

#### Frontend setup
```
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<!-- include FilePond library -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

<!-- include FilePond plugins -->
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

<!-- include FilePond jQuery adapter -->
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
<script type="text/javascript">
  jQuery(document).ready(function() {

  });
  // Turn input element into a pond
  FilePond.setOptions({
      server: {
          url: '/filepond/api',
          process: '/process',
          revert: '/process',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
      }
  });
  $('.my-pond').filepond(); // Here .my-pond is the class name of your filepond input file
</script>
```

#### Backend setup in controller
```
<?php
...
use Cnviradiya\LaravelFilepond\Filepond;

class YourController extends Controller
{
  // This is the demo function you have to write this code in your actual function
  public function update(Request $request, Filepond $filepond)
  {
    // Start file upload process from temp to your actual directory
    $path = $filepond->getPathFromServerId($request->input('upload_file')); // Here upload_file is your name of your element
    $pathArr = explode('.', $path);
    $imageExt = '';
    if (is_array($pathArr)) {
        $imageExt = end($pathArr);
    }
    $fileName = 'upload_file.' . $imageExt;
    $finalLocation = storage_path('uploads/' . $fileName);
    \File::move($path, $finalLocation);
  }
}
```
