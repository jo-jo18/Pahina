<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Book;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getSalesReports(Request $request)
    {
        $period = $request->get('period', 'today');
        
        try {
            $query = Order::where('payment_status', 'Paid')
                ->where('order_status', 'delivered');

            $date = now();
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', $date->toDateString());
                    break;
                case 'week':
                    $query->where('created_at', '>=', $date->subDays(7));
                    break;
                case 'month':
                    $query->where('created_at', '>=', $date->subMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', $date->subYear());
                    break;
                case 'all':
                    break;
            }

            $orders = $query->get();

            $totalRevenue = $orders->sum('total');
            $codRevenue = $orders->where('payment_method', 'Cash on Delivery')->sum('total');
            $bankRevenue = $orders->where('payment_method', 'Bank Transfer')->sum('total');
            $totalOrders = $orders->count();
            $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

            $pendingPayments = Order::whereIn('payment_status', ['Pending', 'Awaiting Payment'])
                ->where('approval_status', '!=', 'cancelled')
                ->count();

            $topBooks = DB::table('order_items')
                ->join('books', 'order_items.book_id', '=', 'books.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.payment_status', 'Paid')
                ->where('orders.order_status', 'delivered');
            
            if ($period !== 'all') {
                $dateFilter = now();
                switch ($period) {
                    case 'today':
                        $topBooks->whereDate('orders.created_at', $dateFilter->toDateString());
                        break;
                    case 'week':
                        $topBooks->where('orders.created_at', '>=', $dateFilter->subDays(7));
                        break;
                    case 'month':
                        $topBooks->where('orders.created_at', '>=', $dateFilter->subMonth());
                        break;
                    case 'year':
                        $topBooks->where('orders.created_at', '>=', $dateFilter->subYear());
                        break;
                }
            }
            
            $topBooks = $topBooks->select(
                    'books.id',
                    'books.title',
                    'books.author',
                    DB::raw('SUM(order_items.quantity) as total_sold'),
                    DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
                )
                ->groupBy('books.id', 'books.title', 'books.author')
                ->orderBy('total_sold', 'desc')
                ->limit(5)
                ->get();

            $this->saveReport($period, [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'total_books_sold' => $topBooks->sum('total_sold'),
                'average_order_value' => $avgOrderValue,
                'top_books' => $topBooks,
            ]);

            return response()->json([
                'stats' => [
                    'total_revenue' => $totalRevenue,
                    'cod_revenue' => $codRevenue,
                    'bank_revenue' => $bankRevenue,
                    'total_orders' => $totalOrders,
                    'avg_order_value' => $avgOrderValue,
                    'pending_payments' => $pendingPayments,
                ],
                'top_books' => $topBooks,
                'period' => $period,
                'generated_at' => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'stats' => [
                    'total_revenue' => 0,
                    'cod_revenue' => 0,
                    'bank_revenue' => 0,
                    'total_orders' => 0,
                    'avg_order_value' => 0,
                    'pending_payments' => 0,
                ],
                'top_books' => [],
                'period' => $period,
            ], 500);
        }
    }

    private function saveReport($period, $data)
    {
        try {
            $reportDate = now()->toDateString();
            
            Report::updateOrCreate(
                [
                    'report_type' => $period,
                    'report_date' => $reportDate,
                ],
                [
                    'total_revenue' => $data['total_revenue'],
                    'total_orders' => $data['total_orders'],
                    'total_books_sold' => $data['total_books_sold'],
                    'average_order_value' => $data['average_order_value'],
                    'top_books' => json_encode($data['top_books']),
                ]
            );
        } catch (\Exception $e) {
        }
    }

    public function getHistoricalReports(Request $request)
    {
        $type = $request->get('type', 'monthly');
        $limit = $request->get('limit', 12);
        
        try {
            $reports = Report::where('report_type', $type)
                ->orderBy('report_date', 'desc')
                ->limit($limit)
                ->get();
            
            return response()->json($reports);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'today');
        
        try {
            $data = $this->getSalesReports($request);
            $responseData = $data->getData();
            
            $filename = "sales_report_{$period}_" . now()->format('Y-m-d_H-i-s') . ".json";
            
            return response()->json($responseData)
                ->header('Content-Disposition', 'attachment; filename=' . $filename);
                
        } catch (\Exception $e) {
            return response()->json(['error' => 'Export failed'], 500);
        }
    }
}