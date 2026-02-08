<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Service;
use App\Models\Purchase;
use App\Models\DailySale;
use App\Models\Admin\News;
use App\Models\Admin\Size;
use App\Models\Admin\Brand;
use App\Models\SalesTarget;
use App\Models\Admin\Slider;
use App\Models\Admin\Writer;
use App\Models\DailyExpense;
use App\Models\RatingReview;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Subject;
use App\Models\Admin\Category;
use App\Models\Admin\HomePage;
use App\Models\Admin\NavItems;
use App\Models\Admin\Publisher;
use App\Models\Admin\ProductSize;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class FrontendController extends Controller
{
    public function index()
    { 
        $user = auth()->user();

    if ($user->hasRole('Employee')) {
        // For Employees – show limited data
        return view('frontend.pages.dashboard_employee', [
            'title' => 'Employee Dashboard',
            'data' => [], // empty or limited
        ]);
    }
        
        // $todaysRevenue = Service::whereDate('created_at', Carbon::today())->where('status','1')->sum('bill');
        // $thisWeeksRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status','1')->sum('bill');
        // $thisMonthsRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('status','1')->sum('bill');
        // $thisYearsRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status','1')->sum('bill');
        // $totalServiceDues = Service::where('status','1')->where('due_amount', '>', 0)->sum('due_amount');

        $todaysSalesRevenue = Sale::whereDate('created_at', Carbon::today())->sum('payble');
        $thisWeeksSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek(),])->sum('payble');        
        $thisMonthsSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('payble');
        $thisYearsSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->sum('payble');

        $todaysPurchaseRevenue = Purchase::whereDate('created_at', Carbon::today())
         
            ->sum('total_price');

        $thisWeeksPurchaseRevenue = Purchase::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
          
            ->sum('total_price');

        $thisMonthsPurchaseRevenue = Purchase::whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
         
            ->sum('total_price');

        $thisYearsPurchaseRevenue = Purchase::whereBetween('created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear()
            ])
          
            ->sum('total_price');

        // $todaysExpense = DailyExpense::whereDate('date', Carbon::today())
        //     ->sum('amount');

        // $thisWeeksExpense = DailyExpense::whereBetween('date', [
        //         Carbon::now()->startOfWeek(),
        //         Carbon::now()->endOfWeek()
        //     ])
        //     ->sum('amount');

        // $thisMonthsExpense = DailyExpense::whereBetween('date', [
        //         Carbon::now()->startOfMonth(),
        //         Carbon::now()->endOfMonth()
        //     ])
        //     ->sum('amount');

        // $thisYearsExpense = DailyExpense::whereBetween('date', [
        //         Carbon::now()->startOfYear(),
        //         Carbon::now()->endOfYear()
        //     ])
        //     ->sum('amount');
        
        
       return view('frontend.pages.index', compact(
            'todaysSalesRevenue',
            'thisWeeksSalesRevenue',
            'thisMonthsSalesRevenue',
            'thisYearsSalesRevenue',
            'todaysPurchaseRevenue',
            'thisWeeksPurchaseRevenue',
            'thisMonthsPurchaseRevenue',
            'thisYearsPurchaseRevenue',
            // 'todaysExpense',
            // 'thisWeeksExpense',
            // 'thisMonthsExpense',
            // 'thisYearsExpense'
        ));

      
    }

    public function about()
    {
        $services = Category::get();
        return view('frontend.pages.about',compact('services'));
    }

    public function ourServices()
    {
        $services = Category::get();
        return view('frontend.pages.service',compact('services'));
    }

    public function projects()
    {
        return view('frontend.pages.projects');
    }

    public function shop()
    {
        // $news = News::join('categories', 'categories.id', '=', 'news.category_id')->select('news.*', 'categories.name')->where('news.status', '1')->orderBy('news.id', 'desc')->paginate(6);
        $products = Product::where('status','1')->paginate(5);
        return view('frontend.pages.shop', compact('products'));
    }

    
    public function productDetails($id)
    {


        // return soundex('Example Product');


        // $rows = Subject::get();

        // foreach($rows as $row){
        //     $row->search_string = $row->name.' '.getBanglish($row->name);
        //     $row->update();
        // }
        // return;


        $product = Product::where('id', $id)->first();

        if(!$product) abort(404);

        $productSizes = [];
        if($product->is_size == '1'){
            $productSizes = ProductSize::join('sizes', 'sizes.id', 'product_sizes.size_id')
                            ->where('product_sizes.product_id', $product->id)
                            ->where('product_sizes.status', '1')
                            ->select('product_sizes.*','sizes.name')->get();
        }

        $packageItems = [];
        if($product->is_book_or_product == '1'){
            if($product->is_package){
                $Products = Product::whereIn('id', explode(',', $product->package_item_ids))
                ->get(['writer_id', 'publisher_id', 'subject_id', 'name', 'id']);
                $writerIds = $Products->pluck('writer_id')->unique();
                $publisherIds = $Products->pluck('publisher_id')->unique();
                $subjectIds = $Products->pluck('subject_id')->unique();
                $packageItems = $Products->pluck('name','id');

                $writerIds = $writerIds->flatMap(function($ids) {return explode(',', $ids);})->unique()->implode(',');
                $publisherIds = $publisherIds->flatMap(function($ids) {return explode(',', $ids);})->unique()->implode(',');
                $subjectIds = $subjectIds->flatMap(function($ids) {return explode(',', $ids);})->unique()->implode(',');

                $product->writer_id = $writerIds;
                $product->publisher_id = $publisherIds;
                $product->subject_id = $subjectIds;
            }
        }

        $ratingReviews = RatingReview::join('users','users.id', '=', 'rating_reviews.user_id')
                        ->where('product_id', $product->id)
                        ->select('rating_reviews.*','users.name','users.email')
                        ->get();

        $totalReviews = count($ratingReviews);
        $totalRatingPoint = 0;
        $ratings = ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0,];
        foreach($ratingReviews as $ratingReview){
            $totalRatingPoint += $ratingReview->rating;
            $ratings[$ratingReview->rating]++;
        }
        // return $totalRating;

        if($product->is_book_or_product == '1'){
            $relatedProduct = Product::where('subject_id',$product->subject_id)->take(12)->get();
        }
        elseif($product->is_book_or_product == '2'){
            $relatedProduct = Product::where('category_id',$product->category_id)->take(12)->get();
        }

        

        $libWriters = lib_writer();
        $libSubjets = lib_subject();
        $libPublishers = lib_publisher();
        $libBrands = lib_brand();
        $libCategories = lib_category();
        return view('frontend.pages.productDetails',compact('totalReviews','totalRatingPoint','ratings','product','ratingReviews','relatedProduct','productSizes','libWriters','libSubjets','libPublishers','libBrands','libCategories','packageItems'));
    }

    public function checkout() {
        return view('frontend.pages.checkout');
    }

    public function getCheckOutPage(){
        $html = (string)generateCheckOutPage();
        return ['html' => $html,];
    }

    public function cart() {
        return view('frontend.pages.cart');
    }

    public function getCart(){
        return [
            'html' => (string)cartView(),
            'miniCartHtml' => (string)cartMiniView(),
            'currectPrice'=> getTotalcartValue(),
        ];
    }

    public function whatWeDo()
    {
        return view('frontend.pages.pages_what_we_do');
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }



    public function singleHistory()
    {
        return view('frontend.pages.history_signle');
    }

    public function newsSingle($id)
    {
        $singleNews = News::join('categories', 'categories.id', '=', 'news.category_id')->select('news.*', 'categories.name')->where('news.id', $id)->first();
        return view('frontend.pages.news_single', compact('singleNews'));
    }


    public function subjects() {
        $libSubjets = lib_subject();
        return view('frontend.pages.subject', compact('libSubjets'));
    }

    public function subjectWiseProducts($id) {
        $libSubjets = lib_subject();
        $libWriters = lib_writer();
        $books = Product::whereRaw('FIND_IN_SET(?, subject_id)', $id)->where('status','1')->orderBy('id','desc')->get();
        $bookcount = Product::whereRaw('FIND_IN_SET(?, subject_id)', $id)->where('status','1')->orderBy('id','desc')->count();
        return view('frontend.pages.subjectwisebook', compact('libSubjets','books','bookcount','libWriters'));
    }

    public function authors() {
        $libWriters = lib_writer();
        return view('frontend.pages.author', compact('libWriters'));
    }

    public function authorWiseProducts($id) {
        $libSubjets = lib_subject();
        $libWriters = lib_writer();
        $books = Product::whereRaw('FIND_IN_SET(?, writer_id)', $id)->where('status','1')->orderBy('id','desc')->get();
        $bookcount = Product::whereRaw('FIND_IN_SET(?, writer_id)', $id)->where('status','1')->orderBy('id','desc')->count();
        return view('frontend.pages.writerwisebook', compact('libSubjets','books','libWriters','bookcount'));
    }

    public function pulishers() {
        $lib_publisher = lib_publisher();
        return view('frontend.pages.publisher', compact('lib_publisher'));
    }

    public function pulisherWiseProducts($id) {
        $libSubjets = lib_subject();
        $libWriters = lib_writer();
        $books = Product::whereRaw('FIND_IN_SET(?, publisher_id)', $id)->where('status','1')->orderBy('id','desc')->get();
        $bookcount = Product::whereRaw('FIND_IN_SET(?, publisher_id)', $id)->where('status','1')->orderBy('id','desc')->count();
        return view('frontend.pages.publisherwisebook', compact('libSubjets','bookcount','libWriters','books','bookcount'));
    }
    public function filterProduct(Request $request){

        $filter_type = $request->type;
        $category = explode(',', $request->category);
        $brand = explode(',', $request->brand);
        $publisher = explode(',', $request->publisher);
        $writer = explode(',', $request->writer);
        $subject = explode(',', $request->subject);
        if(!$filter_type || !in_array($filter_type, ['product','book'])) abort(404);

        $lib_category = lib_category();
        $lib_book_category = lib_book_category();
        $lib_brand = lib_brand();
        $lib_publisher = lib_publisher();
        $lib_writer = lib_writer();
        $lib_subject = lib_subject();
        return view('frontend.pages.itemFilter', compact('filter_type','category','brand','publisher','writer','subject','lib_category','lib_book_category','lib_brand','lib_publisher','lib_writer','lib_subject'));
    }

    public function filter(Request $request){
        // return $request->all();
        $category = $request->category;
        $brand = $request->brand;
        $publisher = $request->publisher;
        $writer = $request->writer;
        $subject = $request->subject;
        $sort_by = $request->sort_by??1;

        $products = Product::query();

        if(count($category)){
            $products = $products->whereRaw(getArrayCond('category_id', $category));
        }
        if(count($brand)){
            $products = $products->whereRaw(getArrayCond('brand_id', $brand));
        }
        if(count($publisher)){
            $products = $products->whereRaw(getArrayCond('publisher_id', $publisher));
        }
        if(count($writer)){
            $products = $products->whereRaw(getArrayCond('writer_id', $writer));
        }
        if(count($subject)){
            $products = $products->whereRaw(getArrayCond('subject_id', $subject));
        }

        if(count($category) || count($brand) || count($publisher) || count($writer) || count($subject)){
            $products = $products->paginate(5);
        }else $products = [];

        $lib_all_category = lib_all_category();
        $lib_brand = lib_brand();
        $lib_publisher = lib_publisher();
        $lib_writer = lib_writer();
        $lib_subject = lib_subject();

        $html = (string) view('frontend.pages.filteredProduct', compact('category','brand','publisher','writer','subject','sort_by','products','lib_all_category','lib_brand','lib_publisher','lib_writer','lib_subject'));

        return [
            'html' => $html,
        ];
        
        
    }

    public function freeSearch(Request $request){
        // return $request->key;
        $products = Product::leftJoin('writers', 'writers.id', '=', 'products.writer_id')
                    ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                    ->where(function($query) use ($request) {
                        $query->whereRaw("SOUNDEX(products.search_string) = SOUNDEX(?)", [$request->key])
                              ->orWhereRaw("SOUNDEX(writers.search_string) = SOUNDEX(?)", [$request->key])
                              ->orWhereRaw("SOUNDEX(brands.search_string) = SOUNDEX(?)", [$request->key])
                              ->orWhere('products.search_string', 'LIKE', '%' . $request->key . '%')
                              ->orWhere('writers.search_string', 'LIKE', '%' . $request->key . '%')
                              ->orWhere('brands.search_string', 'LIKE', '%' . $request->key . '%');
                    })
                    ->select('products.*', 'writers.name as writer_name', 'brands.name as brand_name')
                    ->get();

        $lib_writer = lib_writer();
        $lib_brand = lib_brand();

        $totalItem = count($products);
        $html = (string) view('frontend.layouts.free_search', compact('products','lib_writer','lib_brand'));

        return [
            'html' => $html,
            'totalItem' => $totalItem,
        ];


    }
}