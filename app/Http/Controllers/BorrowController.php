<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Book;
use App\Person;
use App\Transaction;
use App\Project;
use App\Http\Requests\PeminjamanRequest;
use Illuminate\Http\Request;

class BorrowController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //$books = Book::available()->orderByTitle()->lists('title', 'id');
        $persons = Person::active()->orderByName()->pluck('last_name', 'id');
        //$categories = Category::lists('category', 'id');
        $categories = Project::get();

        return view('rdr.borrow.borrow', compact('persons', 'categories'));
    }

    public function store(PeminjamanRequest $request)
    {
        $input = $request->all();
        $book_id = $input['book_id'];
        $person_id = $input['person_id'];
        $input['borrowed_at'] = time();
        $transaction = Transaction::create($input);
        $book = Book::findOrFail($book_id);
        $stock = $book['stock'] - 1;
        $book->update(['stock' => $stock]);
        $person = Person::findOrFail($person_id);
        $borrow = $person['borrow'] + 1;
        $person->update(['borrow' => $borrow]);
        session()->flash('flash_message', 'You have added 1 transaction!');

        return redirect()->route('borrow.report');
    }

    public function report()
    {
        $dateNow = time();
        $transactions = Transaction::with('student', 'book')->notReturnedYet()->get();

        foreach ($transactions as $transaction) {
            $dateDiff = $dateNow - $transaction['borrowed_at'];
            $durasi = floor($dateDiff/(60 * 60 * 24));
            // $fines = Fine::first();
            // if($durasi > $fines['days'])
            // {
            // $hariDenda = $durasi - $fines['days'];
            // $denda = $hariDenda * $fines['fines'];
            // $transaction->update(['fines' => $denda]);
            // }
            // else
            // {
            // $denda = 0;
            // $transaction->update(['fines' => $denda]);
            // }
        }
        //ambil tanggal
        //$date2 = mktime(0,0,0,05,31,2015);
        //return $date2;
        return view('rdr.borrow.report', compact('transactions', 'durasi'));
    }

    public function pengembalian($id)
    {
        $returnedAt = time();
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 1, 'returned_at' => $returnedAt]);

        $book = Book::findOrFail($transaction['book_id']);
        $stock = $book['stock'] + 1;
        $book->update(['stock' => $stock]);
        $student = Student::findOrFail($transaction['student_id']);
        $borrow = $student['borrow'] - 1;
        $student->update(['borrow' => $borrow]);

        session()->flash('flash_message', 'You have returned 1 book!');
        return redirect()->route('borrow.histori');
    }

    public function perpanjang($id)
    {
        $transaction = Transaction::findOrFail($id);
        $dateNow = time();
        $transaction->update(['borrowed_at' => $dateNow, 'fines' => 0]);
        session()->flash('flash_message', 'You have added 1 perpanjang!');
        return redirect()->route('borrow.report');
    }

    public function histori()
    {
        $transactions = Transaction::returned()->get();
        return view('rdr.borrow.histori', compact('transactions'));
    }
}
