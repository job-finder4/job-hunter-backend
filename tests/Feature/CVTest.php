<?php

namespace Tests\Feature;

use App\Models\Cv;
use App\Models\User;
use Dompdf\Adapter\PDFLib;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CVTest extends TestCase
{


    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /**
     * @group dani
     */

    /**
     * @test
     */
    public function a_user_can_upload_cv_file()
    {
        $this->withoutExceptionHandling();
        $sizeInKilobytes = 1000;
        $file = UploadedFile::fake()->create(
            'document.pdf', $sizeInKilobytes, 'application/pdf'
        );

        $this->actingAs($user = User::factory()->create(), 'api');
        $resp = $this->post('/api/cvs', [
            'cv_file' => $file,
            'title' => 'dsa'
        ])->assertStatus(201);

        $uniqueName = 'cvs/' . $user->id . '/' . $file->getClientOriginalName();
        Storage::disk('local')->assertExists($uniqueName);

        $cv = Cv::first();
        $this->assertCount(1, Cv::all());
        $this->assertEquals($user->id, $cv->user_id);

        $resp->assertJson([
            'data' => [
                'type' => 'cvs',
                'id' => $cv->id,
                'attributes' => [
                    'title' => $cv->title,
                    'user_id' => $cv->user_id,
                    'download_link' => '/api/cvs/' . $cv->id . '/download'
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function a_title_field_is_required_to_create_a_cv()
    {
        $sizeInKilobytes = 1000;
        $file = UploadedFile::fake()->create(
            'document.pdf', $sizeInKilobytes, 'application/pdf'
        );

        $this->actingAs($user = User::factory()->create(), 'api');
        $resp = $this->post('/api/cvs', [
            'cv_file' => $file
        ])->assertStatus(422);

        $responseString = json_decode($resp->getContent(), true);
        $this->assertArrayHasKey('title', $responseString['errors']['meta']);
    }

    /**
     * @test
     */
    public function a_cv_file_is_required_to_create_a_cv()
    {
        $sizeInKilobytes = 1000;
        $file = UploadedFile::fake()->create(
            'document.pdf', $sizeInKilobytes, 'application/pdf'
        );

        $this->actingAs($user = User::factory()->create(), 'api');
        $resp = $this->post('/api/cvs', [
            'title' => 'first_cv'
        ])->assertStatus(422);

        $responseString = json_decode($resp->getContent(), true);
        $this->assertArrayHasKey('cv_file', $responseString['errors']['meta']);
    }

    /**
     * @test
     */
    public function cv_files_should_not_exceed_4m_byte()
    {
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');
        $sizeInKilobytes = 5000;
        $file = UploadedFile::fake()->create(
            'document.pdf', $sizeInKilobytes, 'application/pdf'
        );

        $resp = $this->post('/api/cvs', [
            'cv_file' => $file
        ])->assertStatus(422);

        $uniqueName = '/cvs/' . $user->id . '/' . $file->getClientOriginalName();
        Storage::disk('local')->missing($uniqueName);

        $cv = Cv::first();
        $this->assertCount(0, Cv::all());
    }

//    /**
//     * @test
//     */
//    public function cv_file_can_be_downloaded()
//    {
//        $this->withoutExceptionHandling();
//        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');
////
//        $sizeInKilobytes = 200;
//        $file = UploadedFile::fake()->create(
//            'document.pdf', $sizeInKilobytes, 'application/pdf'
//        );
//
//        $this->post('/api/cvs', [
//            'cv_file' => $file,
//            'title' => 'dsa'
//        ]);
//
//        $cv = Cv::first();
//        $resp = $this->call('GET', '/api/cvs/' . $cv->id . '/download');
//
//        $resp->assertHeader('Content-Type', 'application/pdf');
//    }


    //---------------------------new daniel test---------------------------------
    /**
     * @test
     */
    public function user_can_retrieve_his_cvs()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = \App\Models\User::factory()->create(), 'api');
        Cv::factory()->count(2)->create(['user_id' => $user->id]);

        $cv1 = Cv::orderBy('id')->first();
        $cv2 = Cv::orderByDesc('id')->first();

        $resp = $this->get('/api/user/my-cvs');
//        dd($resp->getContent());

        $resp->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'cvs',
                        'id' => $cv1->id,
                        'attributes' => [
                            "title" => $cv1->title,
                            "user_id" => $user->id,
                            "download_link" => '/api/cvs/' . $cv1->id . '/download'
                        ]
                    ]
                ],
                [
                    'data' => [
                        'type' => 'cvs',
                        'id' => $cv2->id,
                        'attributes' => [
                            "title" => $cv2->title,
                            "user_id" => $user->id,
                            "download_link" => '/api/cvs/' . $cv2->id . '/download'
                        ]
                    ]
                ],
            ]
        ]);
    }
}
