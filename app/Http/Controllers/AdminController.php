<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

use App\Models\User;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        ## test ??
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $email = $request->email;
        $password = $request->password;

        $user_info = DB::table('users')->select(['password', 'name', 'id'])->where('email', $email)->first();

        # dd($user_info);

        $db_name = $user_info->name;
        $db_password = $user_info->password;
        $user_id = $user_info->id;

        if (Hash::check($password, $db_password)) {
            $request->session()->put('email', $email);
            session(['email' => $email, 'name' => $db_name, 'user_id' => $user_id]);
            return redirect()->route('admin.dashboard');
        } else {
            return back()->with('error', 'Unable to authenticate user');
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|unique:users|email|max:50',
            'password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:password'
        ]);

        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $saved = $user->save();

        if (!$saved) {
            return back()->with('error', 'Error registering user');
        } else {
            return redirect()->route('admin.login')->with('success', 'You have successfully registered! Please login');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('admin.login')->with('success', 'You have successfully logged out');
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function get_thumb_image_name($image_file)
    {
        return pathinfo($image_file)['filename'] . '-thumb.' . (pathinfo($image_file)['extension']);
    }

    public function profile()
    {
        $user = DB::table('users')->where('email', session('email'))->first();
        $thumb_image = $this->get_thumb_image_name($user->image);
        $allCountry = $this->getCountry();

        return view('admin.profile', ['user' => $user, 'thumb_image' => $thumb_image, 'countries' => $allCountry]);
    }

    public function add()
    {
        return view('admin.expense.add');
    }

    public function list()
    {
        return view('admin.expense.list');
    }

    public function profileSave(Request $request)
    {
        $data = [
            'name' => $request->name,
            'about' => $request->about,
            'company' => $request->company,
            'role' => $request->role,
            'address' => $request->address,
            'country' => $request->country,
            'phone' => $request->phone,
            'twitter' => $request->twitter,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
        ];

        # if a new file has been uploaded
        if (!is_null($request->file('profile_photo'))) {

            $image_extension = $request->file('profile_photo')->extension();

            # new image name {profile_name}_time()
            $profile_name = Str::kebab(session('name')) . '-' . time();
            $new_image_name = $profile_name . '.' . $image_extension;

            # store the image in local disk, it will return the file path
            $path = $request->file('profile_photo')->storeAs(env('ADMIN_PROFILE_PHOTO_DIR'), $new_image_name, 'admin');

            # update the image path in database
            $data['image'] = $path;

            $new_image_name_thumb = $profile_name . '-thumb.' . $image_extension;

            $resize_image = Image::make($request->file('profile_photo')->getRealPath());
            $resize_image->resize(120, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path() . env('ADMIN_PHOTO_PATH') . env('ADMIN_PROFILE_PHOTO_DIR') . '/' . $new_image_name_thumb);

            # delete old image from disk
            $old_image_name = DB::table('users')->where('email', session('email'))->select('image')->first()->image;

            Storage::disk('admin')->delete($old_image_name);
            Storage::disk('admin')->delete(env('ADMIN_PROFILE_PHOTO_DIR') . '/' . $this->get_thumb_image_name($old_image_name));

        }

        /*
        Query builder returns false when no field is changed and "Save Changes" button is pressed in the form
        $updated = DB::table('users')->where('email', session('email'))->update($data);
        */

        # Eloquent ORM
        $updated = User::where('email', session('email'))->update($data);

        if (!$updated) {
            Log::channel('slack')->info('Something happened!');
            return back()->with('error', 'Error saving the profile');
        } else {
            return back()->with('success', 'Profile Saved');
        }
    }

    public function profilePhotoUpload(Request $request)
    {
        if (!is_null($request->file('profile_photo'))) {
            $image_extension = $request->file('profile_photo')->extension();

            # new image name {profile_name}_time()
            $profile_name = Str::kebab(session('name')) . '-' . time();
            $new_image_name = $profile_name . '.' . $image_extension;

            # store the image in local disk, it will return the file path
            $path = $request->file('profile_photo')->storeAs(env('ADMIN_PROFILE_PHOTO_DIR'), $new_image_name, 'admin');

            # update the image path in database
            $data['image'] = $path;

            $new_image_name_thumb = $profile_name . '-thumb.' . $image_extension;

            $resize_image = Image::make($request->file('profile_photo')->getRealPath());
            $resize_image->resize(120, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path() . env('ADMIN_PHOTO_PATH') . env('ADMIN_PROFILE_PHOTO_DIR') . '/' . $new_image_name_thumb);

            # delete old image from disk
            $old_image_name = DB::table('users')->where('email', session('email'))->select('image')->first()->image;

            Storage::disk('admin')->delete($old_image_name);
            Storage::disk('admin')->delete(env('ADMIN_PROFILE_PHOTO_DIR') . '/' . $this->get_thumb_image_name($old_image_name));

            # Eloquent ORM
            $updated = User::where('email', session('email'))->update($data);

            if (!$updated) {
                Log::channel('slack')->info('Something happened!');
            }
        }
    }

    protected function getCountry()
    {
        $country_list = array(
            array("name" => "Afghanistan", "code" => "AF", "id" => 1, "phone_code" => 93, "symbol" => "؋", "capital" => "Kabul", "currency" => "AFN", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "AFG"),
            array("name" => "Aland Islands", "code" => "AX", "id" => 2, "phone_code" => 358, "symbol" => "€", "capital" => "Mariehamn", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "ALA"),
            array("name" => "Albania", "code" => "AL", "id" => 3, "phone_code" => 355, "symbol" => "Lek", "capital" => "Tirana", "currency" => "ALL", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "ALB"),
            array("name" => "Algeria", "code" => "DZ", "id" => 4, "phone_code" => 213, "symbol" => "دج", "capital" => "Algiers", "currency" => "DZD", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "DZA"),
            array("name" => "American Samoa", "code" => "AS", "id" => 5, "phone_code" => 1684, "symbol" => "$", "capital" => "Pago Pago", "currency" => "USD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "ASM"),
            array("name" => "Andorra", "code" => "AD", "id" => 6, "phone_code" => 376, "symbol" => "€", "capital" => "Andorra la Vella", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "AND"),
            array("name" => "Angola", "code" => "AO", "id" => 7, "phone_code" => 244, "symbol" => "Kz", "capital" => "Luanda", "currency" => "AOA", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "AGO"),
            array("name" => "Anguilla", "code" => "AI", "id" => 8, "phone_code" => 1264, "symbol" => "$", "capital" => "The Valley", "currency" => "XCD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "AIA"),
            array("name" => "Antarctica", "code" => "AQ", "id" => 9, "phone_code" => 672, "symbol" => "$", "capital" => "Antarctica", "currency" => "AAD", "continent" => "Antarctica", "continent_code" => "AN", "alpha_3" => "ATA"),
            array("name" => "Antigua and Barbuda", "code" => "AG", "id" => 10, "phone_code" => 1268, "symbol" => "$", "capital" => "St. John's", "currency" => "XCD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "ATG"),
            array("name" => "Argentina", "code" => "AR", "id" => 11, "phone_code" => 54, "symbol" => "$", "capital" => "Buenos Aires", "currency" => "ARS", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "ARG"),
            array("name" => "Armenia", "code" => "AM", "id" => 12, "phone_code" => 374, "symbol" => "֏", "capital" => "Yerevan", "currency" => "AMD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "ARM"),
            array("name" => "Aruba", "code" => "AW", "id" => 13, "phone_code" => 297, "symbol" => "ƒ", "capital" => "Oranjestad", "currency" => "AWG", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "ABW"),
            array("name" => "Australia", "code" => "AU", "id" => 14, "phone_code" => 61, "symbol" => "$", "capital" => "Canberra", "currency" => "AUD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "AUS"),
            array("name" => "Austria", "code" => "AT", "id" => 15, "phone_code" => 43, "symbol" => "€", "capital" => "Vienna", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "AUT"),
            array("name" => "Azerbaijan", "code" => "AZ", "id" => 16, "phone_code" => 994, "symbol" => "m", "capital" => "Baku", "currency" => "AZN", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "AZE"),
            array("name" => "Bahamas", "code" => "BS", "id" => 17, "phone_code" => 1242, "symbol" => "B$", "capital" => "Nassau", "currency" => "BSD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "BHS"),
            array("name" => "Bahrain", "code" => "BH", "id" => 18, "phone_code" => 973, "symbol" => ".د.ب", "capital" => "Manama", "currency" => "BHD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "BHR"),
            array("name" => "Bangladesh", "code" => "BD", "id" => 19, "phone_code" => 880, "symbol" => "৳", "capital" => "Dhaka", "currency" => "BDT", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "BGD"),
            array("name" => "Barbados", "code" => "BB", "id" => 20, "phone_code" => 1246, "symbol" => "Bds$", "capital" => "Bridgetown", "currency" => "BBD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "BRB"),
            array("name" => "Belarus", "code" => "BY", "id" => 21, "phone_code" => 375, "symbol" => "Br", "capital" => "Minsk", "currency" => "BYN", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "BLR"),
            array("name" => "Belgium", "code" => "BE", "id" => 22, "phone_code" => 32, "symbol" => "€", "capital" => "Brussels", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "BEL"),
            array("name" => "Belize", "code" => "BZ", "id" => 23, "phone_code" => 501, "symbol" => "$", "capital" => "Belmopan", "currency" => "BZD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "BLZ"),
            array("name" => "Benin", "code" => "BJ", "id" => 24, "phone_code" => 229, "symbol" => "CFA", "capital" => "Porto-Novo", "currency" => "XOF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "BEN"),
            array("name" => "Bermuda", "code" => "BM", "id" => 25, "phone_code" => 1441, "symbol" => "$", "capital" => "Hamilton", "currency" => "BMD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "BMU"),
            array("name" => "Bhutan", "code" => "BT", "id" => 26, "phone_code" => 975, "symbol" => "Nu.", "capital" => "Thimphu", "currency" => "BTN", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "BTN"),
            array("name" => "Bolivia", "code" => "BO", "id" => 27, "phone_code" => 591, "symbol" => "Bs.", "capital" => "Sucre", "currency" => "BOB", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "BOL"),
            array("name" => "Bonaire, Sint Eustatius and Saba", "code" => "BQ", "id" => 28, "phone_code" => 599, "symbol" => "$", "capital" => "Kralendijk", "currency" => "USD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "BES"),
            array("name" => "Bosnia and Herzegovina", "code" => "BA", "id" => 29, "phone_code" => 387, "symbol" => "KM", "capital" => "Sarajevo", "currency" => "BAM", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "BIH"),
            array("name" => "Botswana", "code" => "BW", "id" => 30, "phone_code" => 267, "symbol" => "P", "capital" => "Gaborone", "currency" => "BWP", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "BWA"),
            array("name" => "Bouvet Island", "code" => "BV", "id" => 31, "phone_code" => 55, "symbol" => "kr", "capital" => "", "currency" => "NOK", "continent" => "Antarctica", "continent_code" => "AN", "alpha_3" => "BVT"),
            array("name" => "Brazil", "code" => "BR", "id" => 32, "phone_code" => 55, "symbol" => "R$", "capital" => "Brasilia", "currency" => "BRL", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "BRA"),
            array("name" => "British Indian Ocean Territory", "code" => "IO", "id" => 33, "phone_code" => 246, "symbol" => "$", "capital" => "Diego Garcia", "currency" => "USD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "IOT"),
            array("name" => "Brunei Darussalam", "code" => "BN", "id" => 34, "phone_code" => 673, "symbol" => "B$", "capital" => "Bandar Seri Begawan", "currency" => "BND", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "BRN"),
            array("name" => "Bulgaria", "code" => "BG", "id" => 35, "phone_code" => 359, "symbol" => "Лв.", "capital" => "Sofia", "currency" => "BGN", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "BGR"),
            array("name" => "Burkina Faso", "code" => "BF", "id" => 36, "phone_code" => 226, "symbol" => "CFA", "capital" => "Ouagadougou", "currency" => "XOF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "BFA"),
            array("name" => "Burundi", "code" => "BI", "id" => 37, "phone_code" => 257, "symbol" => "FBu", "capital" => "Bujumbura", "currency" => "BIF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "BDI"),
            array("name" => "Cambodia", "code" => "KH", "id" => 38, "phone_code" => 855, "symbol" => "KHR", "capital" => "Phnom Penh", "currency" => "KHR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "KHM"),
            array("name" => "Cameroon", "code" => "CM", "id" => 39, "phone_code" => 237, "symbol" => "FCFA", "capital" => "Yaounde", "currency" => "XAF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "CMR"),
            array("name" => "Canada", "code" => "CA", "id" => 40, "phone_code" => 1, "symbol" => "$", "capital" => "Ottawa", "currency" => "CAD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "CAN"),
            array("name" => "Cape Verde", "code" => "CV", "id" => 41, "phone_code" => 238, "symbol" => "$", "capital" => "Praia", "currency" => "CVE", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "CPV"),
            array("name" => "Cayman Islands", "code" => "KY", "id" => 42, "phone_code" => 1345, "symbol" => "$", "capital" => "George Town", "currency" => "KYD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "CYM"),
            array("name" => "Central African Republic", "code" => "CF", "id" => 43, "phone_code" => 236, "symbol" => "FCFA", "capital" => "Bangui", "currency" => "XAF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "CAF"),
            array("name" => "Chad", "code" => "TD", "id" => 44, "phone_code" => 235, "symbol" => "FCFA", "capital" => "N'Djamena", "currency" => "XAF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "TCD"),
            array("name" => "Chile", "code" => "CL", "id" => 45, "phone_code" => 56, "symbol" => "$", "capital" => "Santiago", "currency" => "CLP", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "CHL"),
            array("name" => "China", "code" => "CN", "id" => 46, "phone_code" => 86, "symbol" => "¥", "capital" => "Beijing", "currency" => "CNY", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "CHN"),
            array("name" => "Christmas Island", "code" => "CX", "id" => 47, "phone_code" => 61, "symbol" => "$", "capital" => "Flying Fish Cove", "currency" => "AUD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "CXR"),
            array("name" => "Cocos (Keeling) Islands", "code" => "CC", "id" => 48, "phone_code" => 672, "symbol" => "$", "capital" => "West Island", "currency" => "AUD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "CCK"),
            array("name" => "Colombia", "code" => "CO", "id" => 49, "phone_code" => 57, "symbol" => "$", "capital" => "Bogota", "currency" => "COP", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "COL"),
            array("name" => "Comoros", "code" => "KM", "id" => 50, "phone_code" => 269, "symbol" => "CF", "capital" => "Moroni", "currency" => "KMF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "COM"),
            array("name" => "Congo", "code" => "CG", "id" => 51, "phone_code" => 242, "symbol" => "FC", "capital" => "Brazzaville", "currency" => "XAF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "COG"),
            array("name" => "Congo, Democratic Republic of the Congo", "code" => "CD", "id" => 52, "phone_code" => 242, "symbol" => "FC", "capital" => "Kinshasa", "currency" => "CDF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "COD"),
            array("name" => "Cook Islands", "code" => "CK", "id" => 53, "phone_code" => 682, "symbol" => "$", "capital" => "Avarua", "currency" => "NZD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "COK"),
            array("name" => "Costa Rica", "code" => "CR", "id" => 54, "phone_code" => 506, "symbol" => "₡", "capital" => "San Jose", "currency" => "CRC", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "CRI"),
            array("name" => "Cote D'Ivoire", "code" => "CI", "id" => 55, "phone_code" => 225, "symbol" => "CFA", "capital" => "Yamoussoukro", "currency" => "XOF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "CIV"),
            array("name" => "Croatia", "code" => "HR", "id" => 56, "phone_code" => 385, "symbol" => "kn", "capital" => "Zagreb", "currency" => "HRK", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "HRV"),
            array("name" => "Cuba", "code" => "CU", "id" => 57, "phone_code" => 53, "symbol" => "$", "capital" => "Havana", "currency" => "CUP", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "CUB"),
            array("name" => "Curacao", "code" => "CW", "id" => 58, "phone_code" => 599, "symbol" => "ƒ", "capital" => "Willemstad", "currency" => "ANG", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "CUW"),
            array("name" => "Cyprus", "code" => "CY", "id" => 59, "phone_code" => 357, "symbol" => "€", "capital" => "Nicosia", "currency" => "EUR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "CYP"),
            array("name" => "Czech Republic", "code" => "CZ", "id" => 60, "phone_code" => 420, "symbol" => "Kč", "capital" => "Prague", "currency" => "CZK", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "CZE"),
            array("name" => "Denmark", "code" => "DK", "id" => 61, "phone_code" => 45, "symbol" => "Kr.", "capital" => "Copenhagen", "currency" => "DKK", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "DNK"),
            array("name" => "Djibouti", "code" => "DJ", "id" => 62, "phone_code" => 253, "symbol" => "Fdj", "capital" => "Djibouti", "currency" => "DJF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "DJI"),
            array("name" => "Dominica", "code" => "DM", "id" => 63, "phone_code" => 1767, "symbol" => "$", "capital" => "Roseau", "currency" => "XCD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "DMA"),
            array("name" => "Dominican Republic", "code" => "DO", "id" => 64, "phone_code" => 1809, "symbol" => "$", "capital" => "Santo Domingo", "currency" => "DOP", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "DOM"),
            array("name" => "Ecuador", "code" => "EC", "id" => 65, "phone_code" => 593, "symbol" => "$", "capital" => "Quito", "currency" => "USD", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "ECU"),
            array("name" => "Egypt", "code" => "EG", "id" => 66, "phone_code" => 20, "symbol" => "ج.م", "capital" => "Cairo", "currency" => "EGP", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "EGY"),
            array("name" => "El Salvador", "code" => "SV", "id" => 67, "phone_code" => 503, "symbol" => "$", "capital" => "San Salvador", "currency" => "USD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "SLV"),
            array("name" => "Equatorial Guinea", "code" => "GQ", "id" => 68, "phone_code" => 240, "symbol" => "FCFA", "capital" => "Malabo", "currency" => "XAF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "GNQ"),
            array("name" => "Eritrea", "code" => "ER", "id" => 69, "phone_code" => 291, "symbol" => "Nfk", "capital" => "Asmara", "currency" => "ERN", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "ERI"),
            array("name" => "Estonia", "code" => "EE", "id" => 70, "phone_code" => 372, "symbol" => "€", "capital" => "Tallinn", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "EST"),
            array("name" => "Ethiopia", "code" => "ET", "id" => 71, "phone_code" => 251, "symbol" => "Nkf", "capital" => "Addis Ababa", "currency" => "ETB", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "ETH"),
            array("name" => "Falkland Islands (Malvinas)", "code" => "FK", "id" => 72, "phone_code" => 500, "symbol" => "£", "capital" => "Stanley", "currency" => "FKP", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "FLK"),
            array("name" => "Faroe Islands", "code" => "FO", "id" => 73, "phone_code" => 298, "symbol" => "Kr.", "capital" => "Torshavn", "currency" => "DKK", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "FRO"),
            array("name" => "Fiji", "code" => "FJ", "id" => 74, "phone_code" => 679, "symbol" => "FJ$", "capital" => "Suva", "currency" => "FJD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "FJI"),
            array("name" => "Finland", "code" => "FI", "id" => 75, "phone_code" => 358, "symbol" => "€", "capital" => "Helsinki", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "FIN"),
            array("name" => "France", "code" => "FR", "id" => 76, "phone_code" => 33, "symbol" => "€", "capital" => "Paris", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "FRA"),
            array("name" => "French Guiana", "code" => "GF", "id" => 77, "phone_code" => 594, "symbol" => "€", "capital" => "Cayenne", "currency" => "EUR", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "GUF"),
            array("name" => "French Polynesia", "code" => "PF", "id" => 78, "phone_code" => 689, "symbol" => "₣", "capital" => "Papeete", "currency" => "XPF", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "PYF"),
            array("name" => "French Southern Territories", "code" => "TF", "id" => 79, "phone_code" => 262, "symbol" => "€", "capital" => "Port-aux-Francais", "currency" => "EUR", "continent" => "Antarctica", "continent_code" => "AN", "alpha_3" => "ATF"),
            array("name" => "Gabon", "code" => "GA", "id" => 80, "phone_code" => 241, "symbol" => "FCFA", "capital" => "Libreville", "currency" => "XAF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "GAB"),
            array("name" => "Gambia", "code" => "GM", "id" => 81, "phone_code" => 220, "symbol" => "D", "capital" => "Banjul", "currency" => "GMD", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "GMB"),
            array("name" => "Georgia", "code" => "GE", "id" => 82, "phone_code" => 995, "symbol" => "ლ", "capital" => "Tbilisi", "currency" => "GEL", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "GEO"),
            array("name" => "Germany", "code" => "DE", "id" => 83, "phone_code" => 49, "symbol" => "€", "capital" => "Berlin", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "DEU"),
            array("name" => "Ghana", "code" => "GH", "id" => 84, "phone_code" => 233, "symbol" => "GH₵", "capital" => "Accra", "currency" => "GHS", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "GHA"),
            array("name" => "Gibraltar", "code" => "GI", "id" => 85, "phone_code" => 350, "symbol" => "£", "capital" => "Gibraltar", "currency" => "GIP", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "GIB"),
            array("name" => "Greece", "code" => "GR", "id" => 86, "phone_code" => 30, "symbol" => "€", "capital" => "Athens", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "GRC"),
            array("name" => "Greenland", "code" => "GL", "id" => 87, "phone_code" => 299, "symbol" => "Kr.", "capital" => "Nuuk", "currency" => "DKK", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "GRL"),
            array("name" => "Grenada", "code" => "GD", "id" => 88, "phone_code" => 1473, "symbol" => "$", "capital" => "St. George's", "currency" => "XCD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "GRD"),
            array("name" => "Guadeloupe", "code" => "GP", "id" => 89, "phone_code" => 590, "symbol" => "€", "capital" => "Basse-Terre", "currency" => "EUR", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "GLP"),
            array("name" => "Guam", "code" => "GU", "id" => 90, "phone_code" => 1671, "symbol" => "$", "capital" => "Hagatna", "currency" => "USD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "GUM"),
            array("name" => "Guatemala", "code" => "GT", "id" => 91, "phone_code" => 502, "symbol" => "Q", "capital" => "Guatemala City", "currency" => "GTQ", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "GTM"),
            array("name" => "Guernsey", "code" => "GG", "id" => 92, "phone_code" => 44, "symbol" => "£", "capital" => "St Peter Port", "currency" => "GBP", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "GGY"),
            array("name" => "Guinea", "code" => "GN", "id" => 93, "phone_code" => 224, "symbol" => "FG", "capital" => "Conakry", "currency" => "GNF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "GIN"),
            array("name" => "Guinea-Bissau", "code" => "GW", "id" => 94, "phone_code" => 245, "symbol" => "CFA", "capital" => "Bissau", "currency" => "XOF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "GNB"),
            array("name" => "Guyana", "code" => "GY", "id" => 95, "phone_code" => 592, "symbol" => "$", "capital" => "Georgetown", "currency" => "GYD", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "GUY"),
            array("name" => "Haiti", "code" => "HT", "id" => 96, "phone_code" => 509, "symbol" => "G", "capital" => "Port-au-Prince", "currency" => "HTG", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "HTI"),
            array("name" => "Heard Island and McDonald Islands", "code" => "HM", "id" => 97, "phone_code" => 0, "symbol" => "$", "capital" => "", "currency" => "AUD", "continent" => "Antarctica", "continent_code" => "AN", "alpha_3" => "HMD"),
            array("name" => "Holy See (Vatican City State)", "code" => "VA", "id" => 98, "phone_code" => 39, "symbol" => "€", "capital" => "Vatican City", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "VAT"),
            array("name" => "Honduras", "code" => "HN", "id" => 99, "phone_code" => 504, "symbol" => "L", "capital" => "Tegucigalpa", "currency" => "HNL", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "HND"),
            array("name" => "Hong Kong", "code" => "HK", "id" => 100, "phone_code" => 852, "symbol" => "$", "capital" => "Hong Kong", "currency" => "HKD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "HKG"),
            array("name" => "Hungary", "code" => "HU", "id" => 101, "phone_code" => 36, "symbol" => "Ft", "capital" => "Budapest", "currency" => "HUF", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "HUN"),
            array("name" => "Iceland", "code" => "IS", "id" => 102, "phone_code" => 354, "symbol" => "kr", "capital" => "Reykjavik", "currency" => "ISK", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "ISL"),
            array("name" => "India", "code" => "IN", "id" => 103, "phone_code" => 91, "symbol" => "₹", "capital" => "New Delhi", "currency" => "INR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "IND"),
            array("name" => "Indonesia", "code" => "ID", "id" => 104, "phone_code" => 62, "symbol" => "Rp", "capital" => "Jakarta", "currency" => "IDR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "IDN"),
            array("name" => "Iran, Islamic Republic of", "code" => "IR", "id" => 105, "phone_code" => 98, "symbol" => "﷼", "capital" => "Tehran", "currency" => "IRR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "IRN"),
            array("name" => "Iraq", "code" => "IQ", "id" => 106, "phone_code" => 964, "symbol" => "د.ع", "capital" => "Baghdad", "currency" => "IQD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "IRQ"),
            array("name" => "Ireland", "code" => "IE", "id" => 107, "phone_code" => 353, "symbol" => "€", "capital" => "Dublin", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "IRL"),
            array("name" => "Isle of Man", "code" => "IM", "id" => 108, "phone_code" => 44, "symbol" => "£", "capital" => "Douglas, Isle of Man", "currency" => "GBP", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "IMN"),
            array("name" => "Israel", "code" => "IL", "id" => 109, "phone_code" => 972, "symbol" => "₪", "capital" => "Jerusalem", "currency" => "ILS", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "ISR"),
            array("name" => "Italy", "code" => "IT", "id" => 110, "phone_code" => 39, "symbol" => "€", "capital" => "Rome", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "ITA"),
            array("name" => "Jamaica", "code" => "JM", "id" => 111, "phone_code" => 1876, "symbol" => "J$", "capital" => "Kingston", "currency" => "JMD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "JAM"),
            array("name" => "Japan", "code" => "JP", "id" => 112, "phone_code" => 81, "symbol" => "¥", "capital" => "Tokyo", "currency" => "JPY", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "JPN"),
            array("name" => "Jersey", "code" => "JE", "id" => 113, "phone_code" => 44, "symbol" => "£", "capital" => "Saint Helier", "currency" => "GBP", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "JEY"),
            array("name" => "Jordan", "code" => "JO", "id" => 114, "phone_code" => 962, "symbol" => "ا.د", "capital" => "Amman", "currency" => "JOD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "JOR"),
            array("name" => "Kazakhstan", "code" => "KZ", "id" => 115, "phone_code" => 7, "symbol" => "лв", "capital" => "Astana", "currency" => "KZT", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "KAZ"),
            array("name" => "Kenya", "code" => "KE", "id" => 116, "phone_code" => 254, "symbol" => "KSh", "capital" => "Nairobi", "currency" => "KES", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "KEN"),
            array("name" => "Kiribati", "code" => "KI", "id" => 117, "phone_code" => 686, "symbol" => "$", "capital" => "Tarawa", "currency" => "AUD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "KIR"),
            array("name" => "Korea, Democratic People's Republic of", "code" => "KP", "id" => 118, "phone_code" => 850, "symbol" => "₩", "capital" => "Pyongyang", "currency" => "KPW", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "PRK"),
            array("name" => "Korea, Republic of", "code" => "KR", "id" => 119, "phone_code" => 82, "symbol" => "₩", "capital" => "Seoul", "currency" => "KRW", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "KOR"),
            array("name" => "Kosovo", "code" => "XK", "id" => 120, "phone_code" => 381, "symbol" => "€", "capital" => "Pristina", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "XKX"),
            array("name" => "Kuwait", "code" => "KW", "id" => 121, "phone_code" => 965, "symbol" => "ك.د", "capital" => "Kuwait City", "currency" => "KWD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "KWT"),
            array("name" => "Kyrgyzstan", "code" => "KG", "id" => 122, "phone_code" => 996, "symbol" => "лв", "capital" => "Bishkek", "currency" => "KGS", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "KGZ"),
            array("name" => "Lao People's Democratic Republic", "code" => "LA", "id" => 123, "phone_code" => 856, "symbol" => "₭", "capital" => "Vientiane", "currency" => "LAK", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "LAO"),
            array("name" => "Latvia", "code" => "LV", "id" => 124, "phone_code" => 371, "symbol" => "€", "capital" => "Riga", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "LVA"),
            array("name" => "Lebanon", "code" => "LB", "id" => 125, "phone_code" => 961, "symbol" => "£", "capital" => "Beirut", "currency" => "LBP", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "LBN"),
            array("name" => "Lesotho", "code" => "LS", "id" => 126, "phone_code" => 266, "symbol" => "L", "capital" => "Maseru", "currency" => "LSL", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "LSO"),
            array("name" => "Liberia", "code" => "LR", "id" => 127, "phone_code" => 231, "symbol" => "$", "capital" => "Monrovia", "currency" => "LRD", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "LBR"),
            array("name" => "Libyan Arab Jamahiriya", "code" => "LY", "id" => 128, "phone_code" => 218, "symbol" => "د.ل", "capital" => "Tripolis", "currency" => "LYD", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "LBY"),
            array("name" => "Liechtenstein", "code" => "LI", "id" => 129, "phone_code" => 423, "symbol" => "CHf", "capital" => "Vaduz", "currency" => "CHF", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "LIE"),
            array("name" => "Lithuania", "code" => "LT", "id" => 130, "phone_code" => 370, "symbol" => "€", "capital" => "Vilnius", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "LTU"),
            array("name" => "Luxembourg", "code" => "LU", "id" => 131, "phone_code" => 352, "symbol" => "€", "capital" => "Luxembourg", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "LUX"),
            array("name" => "Macao", "code" => "MO", "id" => 132, "phone_code" => 853, "symbol" => "$", "capital" => "Macao", "currency" => "MOP", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "MAC"),
            array("name" => "Macedonia, the Former Yugoslav Republic of", "code" => "MK", "id" => 133, "phone_code" => 389, "symbol" => "ден", "capital" => "Skopje", "currency" => "MKD", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "MKD"),
            array("name" => "Madagascar", "code" => "MG", "id" => 134, "phone_code" => 261, "symbol" => "Ar", "capital" => "Antananarivo", "currency" => "MGA", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "MDG"),
            array("name" => "Malawi", "code" => "MW", "id" => 135, "phone_code" => 265, "symbol" => "MK", "capital" => "Lilongwe", "currency" => "MWK", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "MWI"),
            array("name" => "Malaysia", "code" => "MY", "id" => 136, "phone_code" => 60, "symbol" => "RM", "capital" => "Kuala Lumpur", "currency" => "MYR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "MYS"),
            array("name" => "Maldives", "code" => "MV", "id" => 137, "phone_code" => 960, "symbol" => "Rf", "capital" => "Male", "currency" => "MVR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "MDV"),
            array("name" => "Mali", "code" => "ML", "id" => 138, "phone_code" => 223, "symbol" => "CFA", "capital" => "Bamako", "currency" => "XOF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "MLI"),
            array("name" => "Malta", "code" => "MT", "id" => 139, "phone_code" => 356, "symbol" => "€", "capital" => "Valletta", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "MLT"),
            array("name" => "Marshall Islands", "code" => "MH", "id" => 140, "phone_code" => 692, "symbol" => "$", "capital" => "Majuro", "currency" => "USD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "MHL"),
            array("name" => "Martinique", "code" => "MQ", "id" => 141, "phone_code" => 596, "symbol" => "€", "capital" => "Fort-de-France", "currency" => "EUR", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "MTQ"),
            array("name" => "Mauritania", "code" => "MR", "id" => 142, "phone_code" => 222, "symbol" => "MRU", "capital" => "Nouakchott", "currency" => "MRO", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "MRT"),
            array("name" => "Mauritius", "code" => "MU", "id" => 143, "phone_code" => 230, "symbol" => "₨", "capital" => "Port Louis", "currency" => "MUR", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "MUS"),
            array("name" => "Mayotte", "code" => "YT", "id" => 144, "phone_code" => 262, "symbol" => "€", "capital" => "Mamoudzou", "currency" => "EUR", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "MYT"),
            array("name" => "Mexico", "code" => "MX", "id" => 145, "phone_code" => 52, "symbol" => "$", "capital" => "Mexico City", "currency" => "MXN", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "MEX"),
            array("name" => "Micronesia, Federated States of", "code" => "FM", "id" => 146, "phone_code" => 691, "symbol" => "$", "capital" => "Palikir", "currency" => "USD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "FSM"),
            array("name" => "Moldova, Republic of", "code" => "MD", "id" => 147, "phone_code" => 373, "symbol" => "L", "capital" => "Chisinau", "currency" => "MDL", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "MDA"),
            array("name" => "Monaco", "code" => "MC", "id" => 148, "phone_code" => 377, "symbol" => "€", "capital" => "Monaco", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "MCO"),
            array("name" => "Mongolia", "code" => "MN", "id" => 149, "phone_code" => 976, "symbol" => "₮", "capital" => "Ulan Bator", "currency" => "MNT", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "MNG"),
            array("name" => "Montenegro", "code" => "ME", "id" => 150, "phone_code" => 382, "symbol" => "€", "capital" => "Podgorica", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "MNE"),
            array("name" => "Montserrat", "code" => "MS", "id" => 151, "phone_code" => 1664, "symbol" => "$", "capital" => "Plymouth", "currency" => "XCD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "MSR"),
            array("name" => "Morocco", "code" => "MA", "id" => 152, "phone_code" => 212, "symbol" => "DH", "capital" => "Rabat", "currency" => "MAD", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "MAR"),
            array("name" => "Mozambique", "code" => "MZ", "id" => 153, "phone_code" => 258, "symbol" => "MT", "capital" => "Maputo", "currency" => "MZN", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "MOZ"),
            array("name" => "Myanmar", "code" => "MM", "id" => 154, "phone_code" => 95, "symbol" => "K", "capital" => "Nay Pyi Taw", "currency" => "MMK", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "MMR"),
            array("name" => "Namibia", "code" => "NA", "id" => 155, "phone_code" => 264, "symbol" => "$", "capital" => "Windhoek", "currency" => "NAD", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "NAM"),
            array("name" => "Nauru", "code" => "NR", "id" => 156, "phone_code" => 674, "symbol" => "$", "capital" => "Yaren", "currency" => "AUD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "NRU"),
            array("name" => "Nepal", "code" => "NP", "id" => 157, "phone_code" => 977, "symbol" => "₨", "capital" => "Kathmandu", "currency" => "NPR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "NPL"),
            array("name" => "Netherlands", "code" => "NL", "id" => 158, "phone_code" => 31, "symbol" => "€", "capital" => "Amsterdam", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "NLD"),
            array("name" => "Netherlands Antilles", "code" => "AN", "id" => 159, "phone_code" => 599, "symbol" => "NAf", "capital" => "Willemstad", "currency" => "ANG", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "ANT"),
            array("name" => "New Caledonia", "code" => "NC", "id" => 160, "phone_code" => 687, "symbol" => "₣", "capital" => "Noumea", "currency" => "XPF", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "NCL"),
            array("name" => "New Zealand", "code" => "NZ", "id" => 161, "phone_code" => 64, "symbol" => "$", "capital" => "Wellington", "currency" => "NZD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "NZL"),
            array("name" => "Nicaragua", "code" => "NI", "id" => 162, "phone_code" => 505, "symbol" => "C$", "capital" => "Managua", "currency" => "NIO", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "NIC"),
            array("name" => "Niger", "code" => "NE", "id" => 163, "phone_code" => 227, "symbol" => "CFA", "capital" => "Niamey", "currency" => "XOF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "NER"),
            array("name" => "Nigeria", "code" => "NG", "id" => 164, "phone_code" => 234, "symbol" => "₦", "capital" => "Abuja", "currency" => "NGN", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "NGA"),
            array("name" => "Niue", "code" => "NU", "id" => 165, "phone_code" => 683, "symbol" => "$", "capital" => "Alofi", "currency" => "NZD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "NIU"),
            array("name" => "Norfolk Island", "code" => "NF", "id" => 166, "phone_code" => 672, "symbol" => "$", "capital" => "Kingston", "currency" => "AUD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "NFK"),
            array("name" => "Northern Mariana Islands", "code" => "MP", "id" => 167, "phone_code" => 1670, "symbol" => "$", "capital" => "Saipan", "currency" => "USD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "MNP"),
            array("name" => "Norway", "code" => "NO", "id" => 168, "phone_code" => 47, "symbol" => "kr", "capital" => "Oslo", "currency" => "NOK", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "NOR"),
            array("name" => "Oman", "code" => "OM", "id" => 169, "phone_code" => 968, "symbol" => ".ع.ر", "capital" => "Muscat", "currency" => "OMR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "OMN"),
            array("name" => "Pakistan", "code" => "PK", "id" => 170, "phone_code" => 92, "symbol" => "₨", "capital" => "Islamabad", "currency" => "PKR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "PAK"),
            array("name" => "Palau", "code" => "PW", "id" => 171, "phone_code" => 680, "symbol" => "$", "capital" => "Melekeok", "currency" => "USD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "PLW"),
            array("name" => "Palestinian Territory, Occupied", "code" => "PS", "id" => 172, "phone_code" => 970, "symbol" => "₪", "capital" => "East Jerusalem", "currency" => "ILS", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "PSE"),
            array("name" => "Panama", "code" => "PA", "id" => 173, "phone_code" => 507, "symbol" => "B/.", "capital" => "Panama City", "currency" => "PAB", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "PAN"),
            array("name" => "Papua New Guinea", "code" => "PG", "id" => 174, "phone_code" => 675, "symbol" => "K", "capital" => "Port Moresby", "currency" => "PGK", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "PNG"),
            array("name" => "Paraguay", "code" => "PY", "id" => 175, "phone_code" => 595, "symbol" => "₲", "capital" => "Asuncion", "currency" => "PYG", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "PRY"),
            array("name" => "Peru", "code" => "PE", "id" => 176, "phone_code" => 51, "symbol" => "S/.", "capital" => "Lima", "currency" => "PEN", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "PER"),
            array("name" => "Philippines", "code" => "PH", "id" => 177, "phone_code" => 63, "symbol" => "₱", "capital" => "Manila", "currency" => "PHP", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "PHL"),
            array("name" => "Pitcairn", "code" => "PN", "id" => 178, "phone_code" => 64, "symbol" => "$", "capital" => "Adamstown", "currency" => "NZD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "PCN"),
            array("name" => "Poland", "code" => "PL", "id" => 179, "phone_code" => 48, "symbol" => "zł", "capital" => "Warsaw", "currency" => "PLN", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "POL"),
            array("name" => "Portugal", "code" => "PT", "id" => 180, "phone_code" => 351, "symbol" => "€", "capital" => "Lisbon", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "PRT"),
            array("name" => "Puerto Rico", "code" => "PR", "id" => 181, "phone_code" => 1787, "symbol" => "$", "capital" => "San Juan", "currency" => "USD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "PRI"),
            array("name" => "Qatar", "code" => "QA", "id" => 182, "phone_code" => 974, "symbol" => "ق.ر", "capital" => "Doha", "currency" => "QAR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "QAT"),
            array("name" => "Reunion", "code" => "RE", "id" => 183, "phone_code" => 262, "symbol" => "€", "capital" => "Saint-Denis", "currency" => "EUR", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "REU"),
            array("name" => "Romania", "code" => "RO", "id" => 184, "phone_code" => 40, "symbol" => "lei", "capital" => "Bucharest", "currency" => "RON", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "ROM"),
            array("name" => "Russian Federation", "code" => "RU", "id" => 185, "phone_code" => 7, "symbol" => "₽", "capital" => "Moscow", "currency" => "RUB", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "RUS"),
            array("name" => "Rwanda", "code" => "RW", "id" => 186, "phone_code" => 250, "symbol" => "FRw", "capital" => "Kigali", "currency" => "RWF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "RWA"),
            array("name" => "Saint Barthelemy", "code" => "BL", "id" => 187, "phone_code" => 590, "symbol" => "€", "capital" => "Gustavia", "currency" => "EUR", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "BLM"),
            array("name" => "Saint Helena", "code" => "SH", "id" => 188, "phone_code" => 290, "symbol" => "£", "capital" => "Jamestown", "currency" => "SHP", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "SHN"),
            array("name" => "Saint Kitts and Nevis", "code" => "KN", "id" => 189, "phone_code" => 1869, "symbol" => "$", "capital" => "Basseterre", "currency" => "XCD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "KNA"),
            array("name" => "Saint Lucia", "code" => "LC", "id" => 190, "phone_code" => 1758, "symbol" => "$", "capital" => "Castries", "currency" => "XCD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "LCA"),
            array("name" => "Saint Martin", "code" => "MF", "id" => 191, "phone_code" => 590, "symbol" => "€", "capital" => "Marigot", "currency" => "EUR", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "MAF"),
            array("name" => "Saint Pierre and Miquelon", "code" => "PM", "id" => 192, "phone_code" => 508, "symbol" => "€", "capital" => "Saint-Pierre", "currency" => "EUR", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "SPM"),
            array("name" => "Saint Vincent and the Grenadines", "code" => "VC", "id" => 193, "phone_code" => 1784, "symbol" => "$", "capital" => "Kingstown", "currency" => "XCD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "VCT"),
            array("name" => "Samoa", "code" => "WS", "id" => 194, "phone_code" => 684, "symbol" => "SAT", "capital" => "Apia", "currency" => "WST", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "WSM"),
            array("name" => "San Marino", "code" => "SM", "id" => 195, "phone_code" => 378, "symbol" => "€", "capital" => "San Marino", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "SMR"),
            array("name" => "Sao Tome and Principe", "code" => "ST", "id" => 196, "phone_code" => 239, "symbol" => "Db", "capital" => "Sao Tome", "currency" => "STD", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "STP"),
            array("name" => "Saudi Arabia", "code" => "SA", "id" => 197, "phone_code" => 966, "symbol" => "﷼", "capital" => "Riyadh", "currency" => "SAR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "SAU"),
            array("name" => "Senegal", "code" => "SN", "id" => 198, "phone_code" => 221, "symbol" => "CFA", "capital" => "Dakar", "currency" => "XOF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "SEN"),
            array("name" => "Serbia", "code" => "RS", "id" => 199, "phone_code" => 381, "symbol" => "din", "capital" => "Belgrade", "currency" => "RSD", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "SRB"),
            array("name" => "Serbia and Montenegro", "code" => "CS", "id" => 200, "phone_code" => 381, "symbol" => "din", "capital" => "Belgrade", "currency" => "RSD", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "SCG"),
            array("name" => "Seychelles", "code" => "SC", "id" => 201, "phone_code" => 248, "symbol" => "SRe", "capital" => "Victoria", "currency" => "SCR", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "SYC"),
            array("name" => "Sierra Leone", "code" => "SL", "id" => 202, "phone_code" => 232, "symbol" => "Le", "capital" => "Freetown", "currency" => "SLL", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "SLE"),
            array("name" => "Singapore", "code" => "SG", "id" => 203, "phone_code" => 65, "symbol" => "$", "capital" => "Singapur", "currency" => "SGD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "SGP"),
            array("name" => "St Martin", "code" => "SX", "id" => 204, "phone_code" => 721, "symbol" => "ƒ", "capital" => "Philipsburg", "currency" => "ANG", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "SXM"),
            array("name" => "Slovakia", "code" => "SK", "id" => 205, "phone_code" => 421, "symbol" => "€", "capital" => "Bratislava", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "SVK"),
            array("name" => "Slovenia", "code" => "SI", "id" => 206, "phone_code" => 386, "symbol" => "€", "capital" => "Ljubljana", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "SVN"),
            array("name" => "Solomon Islands", "code" => "SB", "id" => 207, "phone_code" => 677, "symbol" => "Si$", "capital" => "Honiara", "currency" => "SBD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "SLB"),
            array("name" => "Somalia", "code" => "SO", "id" => 208, "phone_code" => 252, "symbol" => "Sh.so.", "capital" => "Mogadishu", "currency" => "SOS", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "SOM"),
            array("name" => "South Africa", "code" => "ZA", "id" => 209, "phone_code" => 27, "symbol" => "R", "capital" => "Pretoria", "currency" => "ZAR", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "ZAF"),
            array("name" => "South Georgia and the South Sandwich Islands", "code" => "GS", "id" => 210, "phone_code" => 500, "symbol" => "£", "capital" => "Grytviken", "currency" => "GBP", "continent" => "Antarctica", "continent_code" => "AN", "alpha_3" => "SGS"),
            array("name" => "South Sudan", "code" => "SS", "id" => 211, "phone_code" => 211, "symbol" => "£", "capital" => "Juba", "currency" => "SSP", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "SSD"),
            array("name" => "Spain", "code" => "ES", "id" => 212, "phone_code" => 34, "symbol" => "€", "capital" => "Madrid", "currency" => "EUR", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "ESP"),
            array("name" => "Sri Lanka", "code" => "LK", "id" => 213, "phone_code" => 94, "symbol" => "Rs", "capital" => "Colombo", "currency" => "LKR", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "LKA"),
            array("name" => "Sudan", "code" => "SD", "id" => 214, "phone_code" => 249, "symbol" => ".س.ج", "capital" => "Khartoum", "currency" => "SDG", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "SDN"),
            array("name" => "Suriname", "code" => "SR", "id" => 215, "phone_code" => 597, "symbol" => "$", "capital" => "Paramaribo", "currency" => "SRD", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "SUR"),
            array("name" => "Svalbard and Jan Mayen", "code" => "SJ", "id" => 216, "phone_code" => 47, "symbol" => "kr", "capital" => "Longyearbyen", "currency" => "NOK", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "SJM"),
            array("name" => "Swaziland", "code" => "SZ", "id" => 217, "phone_code" => 268, "symbol" => "E", "capital" => "Mbabane", "currency" => "SZL", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "SWZ"),
            array("name" => "Sweden", "code" => "SE", "id" => 218, "phone_code" => 46, "symbol" => "kr", "capital" => "Stockholm", "currency" => "SEK", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "SWE"),
            array("name" => "Switzerland", "code" => "CH", "id" => 219, "phone_code" => 41, "symbol" => "CHf", "capital" => "Berne", "currency" => "CHF", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "CHE"),
            array("name" => "Syrian Arab Republic", "code" => "SY", "id" => 220, "phone_code" => 963, "symbol" => "LS", "capital" => "Damascus", "currency" => "SYP", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "SYR"),
            array("name" => "Taiwan, Province of China", "code" => "TW", "id" => 221, "phone_code" => 886, "symbol" => "$", "capital" => "Taipei", "currency" => "TWD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "TWN"),
            array("name" => "Tajikistan", "code" => "TJ", "id" => 222, "phone_code" => 992, "symbol" => "SM", "capital" => "Dushanbe", "currency" => "TJS", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "TJK"),
            array("name" => "Tanzania, United Republic of", "code" => "TZ", "id" => 223, "phone_code" => 255, "symbol" => "TSh", "capital" => "Dodoma", "currency" => "TZS", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "TZA"),
            array("name" => "Thailand", "code" => "TH", "id" => 224, "phone_code" => 66, "symbol" => "฿", "capital" => "Bangkok", "currency" => "THB", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "THA"),
            array("name" => "Timor-Leste", "code" => "TL", "id" => 225, "phone_code" => 670, "symbol" => "$", "capital" => "Dili", "currency" => "USD", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "TLS"),
            array("name" => "Togo", "code" => "TG", "id" => 226, "phone_code" => 228, "symbol" => "CFA", "capital" => "Lome", "currency" => "XOF", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "TGO"),
            array("name" => "Tokelau", "code" => "TK", "id" => 227, "phone_code" => 690, "symbol" => "$", "capital" => "", "currency" => "NZD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "TKL"),
            array("name" => "Tonga", "code" => "TO", "id" => 228, "phone_code" => 676, "symbol" => "$", "capital" => "Nuku'alofa", "currency" => "TOP", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "TON"),
            array("name" => "Trinidad and Tobago", "code" => "TT", "id" => 229, "phone_code" => 1868, "symbol" => "$", "capital" => "Port of Spain", "currency" => "TTD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "TTO"),
            array("name" => "Tunisia", "code" => "TN", "id" => 230, "phone_code" => 216, "symbol" => "ت.د", "capital" => "Tunis", "currency" => "TND", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "TUN"),
            array("name" => "Turkey", "code" => "TR", "id" => 231, "phone_code" => 90, "symbol" => "₺", "capital" => "Ankara", "currency" => "TRY", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "TUR"),
            array("name" => "Turkmenistan", "code" => "TM", "id" => 232, "phone_code" => 7370, "symbol" => "T", "capital" => "Ashgabat", "currency" => "TMT", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "TKM"),
            array("name" => "Turks and Caicos Islands", "code" => "TC", "id" => 233, "phone_code" => 1649, "symbol" => "$", "capital" => "Cockburn Town", "currency" => "USD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "TCA"),
            array("name" => "Tuvalu", "code" => "TV", "id" => 234, "phone_code" => 688, "symbol" => "$", "capital" => "Funafuti", "currency" => "AUD", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "TUV"),
            array("name" => "Uganda", "code" => "UG", "id" => 235, "phone_code" => 256, "symbol" => "USh", "capital" => "Kampala", "currency" => "UGX", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "UGA"),
            array("name" => "Ukraine", "code" => "UA", "id" => 236, "phone_code" => 380, "symbol" => "₴", "capital" => "Kiev", "currency" => "UAH", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "UKR"),
            array("name" => "United Arab Emirates", "code" => "AE", "id" => 237, "phone_code" => 971, "symbol" => "إ.د", "capital" => "Abu Dhabi", "currency" => "AED", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "ARE"),
            array("name" => "United Kingdom", "code" => "GB", "id" => 238, "phone_code" => 44, "symbol" => "£", "capital" => "London", "currency" => "GBP", "continent" => "Europe", "continent_code" => "EU", "alpha_3" => "GBR"),
            array("name" => "United States", "code" => "US", "id" => 239, "phone_code" => 1, "symbol" => "$", "capital" => "Washington", "currency" => "USD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "USA"),
            array("name" => "United States Minor Outlying Islands", "code" => "UM", "id" => 240, "phone_code" => 1, "symbol" => "$", "capital" => "", "currency" => "USD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "UMI"),
            array("name" => "Uruguay", "code" => "UY", "id" => 241, "phone_code" => 598, "symbol" => "$", "capital" => "Montevideo", "currency" => "UYU", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "URY"),
            array("name" => "Uzbekistan", "code" => "UZ", "id" => 242, "phone_code" => 998, "symbol" => "лв", "capital" => "Tashkent", "currency" => "UZS", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "UZB"),
            array("name" => "Vanuatu", "code" => "VU", "id" => 243, "phone_code" => 678, "symbol" => "VT", "capital" => "Port Vila", "currency" => "VUV", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "VUT"),
            array("name" => "Venezuela", "code" => "VE", "id" => 244, "phone_code" => 58, "symbol" => "Bs", "capital" => "Caracas", "currency" => "VEF", "continent" => "South America", "continent_code" => "SA", "alpha_3" => "VEN"),
            array("name" => "Viet Nam", "code" => "VN", "id" => 245, "phone_code" => 84, "symbol" => "₫", "capital" => "Hanoi", "currency" => "VND", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "VNM"),
            array("name" => "Virgin Islands, British", "code" => "VG", "id" => 246, "phone_code" => 1284, "symbol" => "$", "capital" => "Road Town", "currency" => "USD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "VGB"),
            array("name" => "Virgin Islands, U.s.", "code" => "VI", "id" => 247, "phone_code" => 1340, "symbol" => "$", "capital" => "Charlotte Amalie", "currency" => "USD", "continent" => "North America", "continent_code" => "NA", "alpha_3" => "VIR"),
            array("name" => "Wallis and Futuna", "code" => "WF", "id" => 248, "phone_code" => 681, "symbol" => "₣", "capital" => "Mata Utu", "currency" => "XPF", "continent" => "Oceania", "continent_code" => "OC", "alpha_3" => "WLF"),
            array("name" => "Western Sahara", "code" => "EH", "id" => 249, "phone_code" => 212, "symbol" => "MAD", "capital" => "El-Aaiun", "currency" => "MAD", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "ESH"),
            array("name" => "Yemen", "code" => "YE", "id" => 250, "phone_code" => 967, "symbol" => "﷼", "capital" => "Sanaa", "currency" => "YER", "continent" => "Asia", "continent_code" => "AS", "alpha_3" => "YEM"),
            array("name" => "Zambia", "code" => "ZM", "id" => 251, "phone_code" => 260, "symbol" => "ZK", "capital" => "Lusaka", "currency" => "ZMW", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "ZMB"),
            array("name" => "Zimbabwe", "code" => "ZW", "id" => 252, "phone_code" => 263, "symbol" => "$", "capital" => "Harare", "currency" => "ZWL", "continent" => "Africa", "continent_code" => "AF", "alpha_3" => "ZWE")
        );

        return $country_list;
    }
}
