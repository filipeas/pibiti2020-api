<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Lesion;
use Validator;
use App\Http\Resources\Lesion as LesionResource;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class LesionController extends BaseController
{
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);

        return $file;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lesions = Lesion::all();

        return $this->sendResponse(LesionResource::collection($lesions), 'Lesion retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'analyse' => 'required',
            'original_image' => 'required|image|mimes:jpeg,png,jpg',
            'checked_image' => 'required|image|mimes:jpeg,png,jpg',
            'description' => 'required',
            // 'classified_image' => 'required',
            // 'accuracy' => 'required',
            // 'sensitivity' => 'required',
            // 'specificity' => 'required',
            // 'dice' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $lesion = new Lesion();
        $lesion->analyse = $request->analyse;
        $lesion->description = $request->description;
        $lesion->save();

        if ($request->has('original_image') && $request->has('checked_image')) {
            $imageOrig = $request->file('original_image');
            $imageMark = $request->file('checked_image');

            $nameOrig = Str::slug('_original' . time());
            $nameMark = Str::slug('_marked' . time());

            $folder = '/uploads/base/' . $lesion->id . '/';

            $filePathOrig = $folder . $nameOrig . '.' . $imageOrig->getClientOriginalExtension();
            $filePathMark = $folder . $nameMark . '.' . $imageMark->getClientOriginalExtension();

            $this->uploadOne($imageOrig, $folder, 'public', $nameOrig);
            $this->uploadOne($imageMark, $folder, 'public', $nameMark);

            $lesion->original_image = $filePathOrig;
            $lesion->checked_image = $filePathMark;
        }
        $lesion->save();

        // call script python for processing new image and save the image classified and your statistic data.

        $primeiro = "/home/monstro/Documents/Workspace/Laravel/pibiti2020-api/public/storage/uploads/Script/";
        $segundo = "/home/monstro/Documents/Workspace/Laravel/pibiti2020-api/public/storage{$lesion->original_image}";
        $terceiro = "/home/monstro/Documents/Workspace/Laravel/pibiti2020-api/public/storage{$lesion->checked_image}";
        $result = shell_exec("python /home/monstro/Documents/Workspace/Laravel/pibiti2020-api/public/storage/uploads/Script/runTests.py {$primeiro} {$segundo} {$terceiro} 2>&1 &");
        // dd($result);
        $result2 = strstr($result, 'Result = ');
        $result3 = strstr($result2, ' Fim', true);
        $result3 = strstr($result3, '  ');
        $result3 = str_replace(' ', '', $result3);
        $result3 = str_replace('][', '] [', $result3);

        $result4 = explode(' ', $result3);

        // $lesion->classified_image = ;
        $lesion->accuracy = $result4[0];
        $lesion->sensitivity = $result4[1];
        $lesion->specificity = $result4[2];
        $lesion->dice = $result4[3];
        $lesion->save();

        return $this->sendResponse(new LesionResource($lesion), 'Lesion created successfully. Now let\'s process it for future manipulations.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesion = Lesion::find($id);

        if (is_null($lesion)) {
            return $this->sendError('Lesion not found.');
        }

        return $this->sendResponse(new LesionResource($lesion), 'Lesion retrieved successfully.');
    }

    public function listLesionsByAnalyse($analyse)
    {
        $lesions = Lesion::where('analyse', $analyse)->get();

        if (is_null($lesions)) {
            return $this->sendError('Lesions not found.');
        }

        foreach ($lesions as $key => $value) {
            $value->original_image = asset('storage' . $value->original_image);
            $value->checked_image = asset('storage' . $value->checked_image);
        }

        return $this->sendResponse($lesions, 'Lesions retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesion $lesion)
    {
        // ACREDITO QUE ESSE MÉTODO NÃO SEJA INTERESSANTE DE SER IMPLEMENTADO.
        // SOMENTE É NECESSÁRIO OS MÉTODOS DE CADASTRAR UMA NOVA LESÃO, REMOVER UMA LESÃO
        // E RECLASSIFICAR UMA LESÃO JÁ CADASTRADA. Inclusive, esse método update aqui
        // pode ser responsável somente para reclassificação.
        // $input = $request->all();

        // $validator = Validator::make($input, [
        //     'original_image' => 'required',
        //     'checked_image' => 'required',
        //     'description' => 'required',
        //     // 'classified_image' => 'required',
        //     // 'accuracy' => 'required',
        //     // 'sensitivity' => 'required',
        //     // 'specificity' => 'required',
        //     // 'dice' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return $this->sendError('Validation Error.', $validator->errors());
        // }

        // // remove images saved in the database directory

        // $lesion->original_image = $input['original_image'];
        // $lesion->checked_image = $input['checked_image'];
        // $lesion->description = $input['description'];

        // // now, call script python for processing new image and save the image classified and your statistic data.

        // // $lesion->classified_image = ;
        // // $lesion->accuracy = ;
        // // $lesion->sensitivity = ;
        // // $lesion->specificity = ;
        // // $lesion->dice = ;
        // $lesion->save();

        // return $this->sendResponse(new LesionResource($lesion), 'Lesion updated successfully. Now let\'s process it for future manipulations.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesion $lesion)
    {
        // remove all images of the lesion in directory.
        File::deleteDirectory(storage_path('app/public/uploads/base/' . $lesion->id));

        $lesion->delete();

        return $this->sendResponse([], 'Lesion deleted successfully.');
    }
}
