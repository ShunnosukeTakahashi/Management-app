<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Management;
use App\Summary;
use App\Month;
use App\Calendar;

class MemberController extends Controller
{
    public function index()
    {
      $member = Auth::user();

      return view('sample.index',['member',$member]);
    }


    public function month_list(){

      $month = Month::select()->get();

      return view('sample.month_list',['month'=>$month]);
    }


    public function create($id){

      $day = Calendar::with('month')->where('month_id',$id)->get();
      $month = Month::where('month',$id)->get();

      return view('sample.create',['day'=>$day,'month'=>$month]);
    }


    public function store(Request $request){

      Summary::create([

        'user_id' => $request->input('user_id'),
        'year' => $request->input('year'),
        'month' => $request->input('month_id'),
        'remarks' => $request->input('remarks'),
        'customer' => $request->input('customer'),
        'project' => $request->input('project'),
        'official_strat_time' => $request->input('official_strat_time'),
        'official_end_time' => $request->input('official_end_time'),
        'official_bleak_time' => $request->input('official_bleak_time')]);
      
      
      foreach($request->input(
        
        'opening_time','ending_time',
        'break_time','total_time','over_time',
        'night_time','holiday_time','holiday_night',
        'holiday','adsence','late','leave_early',
        'holiday_work','makeup_holiday','calendar_id') as $key => $value){

        Management::create([

        'opening_time' => $request->input('opening_time')[$key],
        'ending_time' => $request->input('ending_time')[$key],
        'break_time' => $request->input('break_time')[$key],
        'total_time' => $request->input('total_time')[$key],
        'over_time' => $request->input('over_time')[$key],
        'night_time' => $request->input('night_time')[$key],
        'holiday_time' => $request->input('holiday_time')[$key],
        'holiday_night' => $request->input('holiday_night')[$key],
        'holiday' => $request->input('holiday')[$key],
        'adsence' => $request->input('adsence')[$key],
        'late' => $request->input('late')[$key],
        'leave_early' => $request->input('leave_early')[$key],
        'holiday_work' => $request->input('holiday_work')[$key],
        'makeup_holiday' => $request->input('makeup_holiday')[$key],
        'calendar_id' => $request->input('calendar_id')[$key],
        'user_id' => $request->input('user_id'),
        'month_id' => $request->input('month_id'),
        'year' => $request->input('year')]);}

        return redirect('/');
      }


    public function list(){

      $list = Management::groupBy('month_id')->orderBy('month_id','ASC')->get('month_id');
      $auth = Auth::user()->id;

      return view('sample.list',['list'=>$list,'auth'=>$auth]);
    }


    public function show($id,$auth){

      $management = Management::where('month_id',$id)
                  ->whereBetween('calendar_id',[1,31])->get();
      
      $month = Month::where('month',$id)->get();

      $summary = Summary::with('user')->where('user_id',$auth)->get();

      return view('sample.table',['management'=>$management,'month'=>$month,'summary'=>$summary]);
    }


       public function update(Request $request){
        
        $management = Management::where('month_id',$request->month_id)
                      ->select('calendar_id');

        $list = Management::groupBy('calendar_id')->get('calendar_id');
  
        foreach($management as $managements){
  
         $managements->id->update([
  
           'opening_time' => $request->input('opening_time[$list->calendar_id]'),
           'ending_time' => $request->input('ending_time[$list->calendar_id]'),
          'break_time' => $request->input('break_time[$list->calendar_id]'),
          'total_time' => $request->input('total_time[$list->calendar_id]'),
          'over_time' => $request->input('over_time[$list->calendar_id]'),
          'night_time' => $request->input('night_time[$list->calendar_id]'),
          'holiday_time' => $request->input('holiday_time[$list->calendar_id]'),
          'holiday_night' => $request->input('holiday_night[$list->calendar_id]'),
          'holiday' => $request->input('holiday[$list->calendar_id]'),
          'adsence' => $request->input('adsence[$list->calendar_id]'),
          'late' => $request->input('late[$list->calendar_id]'),
          'leave_early' => $request->input('leave_early[$list->calendar_id]'),
          'holiday_work' => $request->input('holiday_work[$list->calendar_id]'),
          'makeup_holiday' => $request->input('makeup_holiday[$list->calendar_id]')]);
        
            }
          return redirect('/');
        }
}         //坂井さんから〜
          //hiddenで配列にして1~31日分のIDを渡しておく
          //1日に対して更新をかけるinputに配列を入れる！
          //これならupdateはできると思うけどupdateするIDの指定ができてない？