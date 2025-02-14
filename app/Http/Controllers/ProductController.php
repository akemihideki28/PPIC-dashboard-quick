<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductLog;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::orderBy('created_at', 'DESC')->get();
  
        return view('products.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();  // Ambil semua data produk yang ada
        return view('products.create', compact('products'));
    }
    
    
        /**
         * Store a newly created resource in storage.
         */
       // Controller
    public function store(Request $request)
    {
        $product = new Product();
        $product->no_mesin = $request->no_mesin;
        $product->nama_mesin = $request->nama_mesin;
        $product->nama_produk = $request->nama_produk;
        $product->part_no = $request->part_no;
        $product->cycle_time = $request->cycle_time;
        $product->cavity = $request->cavity;
        $product->save();
    
        return redirect()->route('products')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
  
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
  
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        
        $oldValues = $product->toArray(); // Simpan nilai lama sebelum update
        $product->update($request->all());
        $newValues = $product->toArray(); // Simpan nilai baru setelah update
    
        // Cek perbedaan antara data lama dan baru
        $changes = [];
        foreach ($oldValues as $key => $oldValue) {
            if ($oldValue != $newValues[$key]) {
                $changes[] = "$key: '$oldValue' → '{$newValues[$key]}'";
            }
        }
    
        // Simpan log perubahan
        ProductLog::create([
            'product_id' => $product->id,
            'no_mesin' => $product->no_mesin, // Pastikan menyimpan nomor mesin
            'action' => 'update',
            'user' => auth()->user()->name, // Pastikan user sudah login
            'detail' => implode(", ", $changes), // Gabungkan perubahan menjadi satu string
        ]);
    
        return redirect()->route('products')->with('success', 'Product updated successfully');
    }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
  
        $product->delete();
  
        return redirect()->route('products')->with('success', 'product deleted successfully');
    }
    /*file download txt*/
    public function downloadTxt()
{
    $products = Product::all(); // Ambil semua data produk

    $txtFileName = 'products.txt';
    $headers = [
        "Content-type" => "text/plain",
        "Content-Disposition" => "attachment; filename=$txtFileName",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    $output = "No\tMesin\tNama Produk\tPart No\tCycle Time\tCavity\tShift 1 Target Qty\tShift 1 Waktu\tShift 2 Target Qty\tShift 2 Waktu\tShift 3 Target Qty\tShift 3 Waktu\tAdded Time\n";

    foreach ($products as $index => $product) {
        $output .= implode("\t", [
            $index + 1,
            $product->nama_mesin,
            $product->nama_produk,
            $product->part_no,
            $product->cycle_time,
            $product->cavity,
            $product->shift1_target_qty,
            $product->shift1_waktu,
            $product->shift2_target_qty,
            $product->shift2_waktu,
            $product->shift3_target_qty,
            $product->shift3_waktu,
            $product->created_at,
        ]) . "\n";
    }

    return response($output, 200, $headers);
}
/*file download json*/
public function downloadJson()
{
    $products = Product::all(); // Ambil semua data produk

    $jsonFileName = 'products.json';
    $headers = [
        "Content-Type" => "application/json",
        "Content-Disposition" => "attachment; filename=$jsonFileName",
    ];

    return response()->json($products)->withHeaders($headers);
}
/*file download xml*/
public function downloadXml()
{
    $products = Product::all(); // Ambil semua data produk

    $xmlFileName = 'products.xml';
    $headers = [
        "Content-Type" => "application/xml",
        "Content-Disposition" => "attachment; filename=$xmlFileName",
    ];

    // Membuat XML
    $xml = new \SimpleXMLElement('<products/>');

    foreach ($products as $product) {
        $productNode = $xml->addChild('product');
        $productNode->addChild('id', $product->id);
        $productNode->addChild('nama_mesin', $product->nama_mesin);
        $productNode->addChild('nama_produk', $product->nama_produk);
        $productNode->addChild('part_no', $product->part_no);
        $productNode->addChild('cycle_time', $product->cycle_time);
        $productNode->addChild('cavity', $product->cavity);
        $productNode->addChild('shift1_target_qty', $product->shift1_target_qty);
        $productNode->addChild('shift1_waktu', $product->shift1_waktu);
        $productNode->addChild('shift2_target_qty', $product->shift2_target_qty);
        $productNode->addChild('shift2_waktu', $product->shift2_waktu);
        $productNode->addChild('shift3_target_qty', $product->shift3_target_qty);
        $productNode->addChild('shift3_waktu', $product->shift3_waktu);
        $productNode->addChild('created_at', $product->created_at);
    }

    return response($xml->asXML(), 200, $headers);
}

public function showLogs()
{
    $logs = ProductLog::with('product')->orderBy('created_at', 'desc')->get();
    return view('products.logs', compact('logs'));
}
}
