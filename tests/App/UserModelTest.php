<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as m;

class UserModelTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function testFirstClearSettleEloquentPayloadAble()
    { 
        
        $user =  $this->createFakeUser();
        
        $user->exists = true;
        
        $this->assertFalse($user->authHasCSJWT());
        
        $this->assertNull($user->getAuthCSMerchantId());
        $this->assertNull($user->getAuthCSMerchantUserId());
        $this->assertNull($user->getAuthCSMerchantUserRole());
        $this->assertNull($user->getAuthCSSPayloadTime());
        $this->assertEmpty($user->getAuthCSSubMerchantIds());
        
    }
        
    /**
     *
     * @return void
     */
    public function testValidJWTClearSettleEloquentPayloadAble()
    { 
        $jwtRepo = m::mock('App\Repositories\JSONWebToken');        
        $jwtRepo->shouldReceive('isStoredByUser')->andReturn(true)->times(1);       
        
        $payload = m::mock('\stdClass');
        
        $payload->merchantId = 1;        
        $payload->merchantUserId = 2;
        $payload->role = 'admin';
        $timestamp = 1444389880;
        $payload->timestamp = $timestamp;
        
        $subMerchanIds = [1,2,3];
        $payload->subMerchantIds = $subMerchanIds;
        
        $jwtRepo->shouldReceive('getPayloadByUser')->andReturn($payload);
       
        $this->app['App\Repositories\JSONWebToken'] = $jwtRepo;
        $user =  $this->createFakeUser();
        
        $user->exists = true;
        
        $this->assertTrue($user->authHasCSJWT());
        
        $this->assertEquals(1, $user->getAuthCSMerchantId());
        $this->assertEquals(2, $user->getAuthCSMerchantUserId());
        $this->assertEquals('admin',$user->getAuthCSMerchantUserRole());
        $this->assertEquals($timestamp, $user->getAuthCSSPayloadTime()->timestamp);
        $this->assertEquals($subMerchanIds, $user->getAuthCSSubMerchantIds());
        
    }
    
    /**
     * create user
     * 
     * @return \App\User
     */
    private function createFakeUser()
    {        
        return factory(App\User::class)->make();
        
    }
}
