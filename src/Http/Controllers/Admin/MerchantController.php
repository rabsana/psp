<?php

namespace Rabsana\Psp\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Rabsana\Core\Support\Facades\Json;
use Rabsana\Psp\Models\Merchant;
use Rabsana\Psp\Http\Requests\Admin\Merchant\StoreMerchantRequest;
use Rabsana\Psp\Http\Requests\Admin\Merchant\UpdateMerchantRequest;
use Illuminate\Support\Str;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $merchants = Merchant::latest()
                ->userId($request->user_id)
                ->name($request->name)
                ->token($request->token)
                ->isActive($request->is_active)
                ->with('user')
                ->paginate($request->get('perPage', 15))
                ->appends($request->all());

            return view("rabsana-psp::admin.merchant.index", [
                'merchants' => $merchants
            ]);

            // 
        } catch (Exception $e) {
            info("Showing-merchants-list-error" . $e);
            return Json::response(500, 'مشکلی در نمایش کسب و کار ها وجود دارد');
        }
    }

    public function document()
    {
        return view("rabsana-psp::admin.merchant.document");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("rabsana-psp::admin.merchant.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Rabsana\Psp\Http\Requests\Admin\Merchant\StoreMerchantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMerchantRequest $request)
    {
        DB::beginTransaction();
        try {

            $merchantId = Merchant::create([
                'user_id'       => $request->safe()->user_id,
                'name'          => $request->safe()->name,
                'token'         => $this->generateUniqueToken(),
                'is_active'     => $request->safe()->is_active,
                'logo'          => $this->getLogoPath(),
            ])->id;

            $merchant = Merchant::findOrFail($merchantId);

            $merchant->currencies()->attach($request->safe()->currency_ids);

            DB::commit();

            return redirect(route("rabsana-psp.admin-api.v1.merchants.index"))->with('success', 'کسب و کار با موفقیت ثبت شد');


            // 
        } catch (Exception $e) {

            DB::rollBack();
            info("Storing-merchant-error: " . $e);
            return back()->with('error', 'مشکلی در ثبت اطلاعات رخ داده است');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rabsana\Psp\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function show(Merchant $merchant)
    {
        //
    }

     /**
     * Display the specified resource.
     *
     * @param  \Rabsana\Psp\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function refreshToken(Merchant $merchant)
    {
        do {
            $new_token = $this->generateUniqueToken();
        } while ( Merchant::where('token', $new_token)->count() > 0);    
    
        $merchant->update([
            'token'=> $new_token
        ]);
      
        return redirect()->route('rabsana-psp.admin-api.v1.merchants.index');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Rabsana\Psp\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function edit(Merchant $merchant)
    {
        $merchant->load('currencies');

        return view("rabsana-psp::admin.merchant.edit", [
            'merchant' => $merchant
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Rabsana\Psp\Http\Requests\Admin\Merchant\UpdateMerchantRequest  $request
     * @param  \Rabsana\Psp\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMerchantRequest $request, Merchant $merchant)
    {
        DB::beginTransaction();
        try {

            $logoPath = $this->getLogoPath();

            $merchant->update([
                'name'          => $request->safe()->name,
                'is_active'     => $request->safe()->is_active,
                'logo'          => $logoPath ?? $merchant->logo,
            ]);

            $merchant->currencies()->sync($request->safe()->currency_ids);

            DB::commit();

            return back()->with('success', 'با موفقیت ویرایش شد');

        //
        } catch (Exception $e) {

            DB::rollBack();
            info("Updating-merchant-error: " . $e);

            return back()->with('error', 'مشکلی در ویرایش کسب و کار وجود دارد');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rabsana\Psp\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Merchant $merchant)
    {
        DB::beginTransaction();
        try {

            $merchant->delete();

            DB::commit();

            return back()->with('success', 'با موفقیت حذف شد');

            // 
        } catch (Exception $e) {

            DB::rollBack();

            return back()->with('error', 'مشکلی در حذف اطلاعات وجود دارد');
        }
    }

    private function generateUniqueToken()
    {
        return Str::random() . time() . Str::random();
    }

    private function getLogoPath()
    {
        if (request()->hasFile('logo')) {
            $logoFile = request()->file('logo');
            $logoName = time() . '.' . $logoFile->getClientOriginalExtension();

            $path = request()->file('logo')->storeAs('merchants', $logoName, 'public');
            $logo = "/storage/$path";

            return $logo;
        }

        return NULL;
    }
}
