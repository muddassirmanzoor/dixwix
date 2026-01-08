<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Validator\Commission as Validator;
use App\Message\Error;
use Exception;

class Commission extends Model
{
    use HasFactory;
    use Validator;
    protected $primaryKey = "id";
    protected $table = "commission";
    protected $fillable = [
        'commission',
        
    ];

    

    public function change(array $data, $book_id, array $change_conditions = [])
    {
        $book = static::find($book_id);
        $data['locations'] = json_encode($data['locations']);

        try {
            $book = parent::change($data, $book_id);
            return $book;
        } catch (Exception $ex) {
            Error::trigger("book.change", [$ex->getMessage()]);
        }
    }
    
  public function add($data){
       
  

        $data['commission'] = $data['commission'];
        

        try {
            $commission = parent::add($data);
          echo $commission;exit();
            return $commission;
        } catch (\Exception $ex) {
            echo $ex->getMessage();exit();
            Error::trigger("commission.add", [$ex->getMessage()]);
            return true;
        }
  }

}
