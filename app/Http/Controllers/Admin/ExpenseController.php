<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyExpenseRequest;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Contact;
use App\Models\Expense;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    

    public function index()
    {
        abort_if(Gate::denies('expense_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $userId = auth()->user()->id;
        $expenses = Expense::where('user_id',$userId)->get();
        $totalIncome = $expenses->where('type', 'Income')->sum('amount');
        $totalExpense = $expenses->where('type', 'Expense')->sum('amount');
        $total = $totalIncome - $totalExpense;
    
        return view('admin.expense.index', compact('expenses','total'));
    }
    public function expense()
    {
         $userId = auth()->user()->id;
        $expenses = Expense::all();
        $totalIncome = $expenses->where('type', 'Income')->sum('amount');
        $totalExpense = $expenses->where('type', 'Expense')->sum('amount');
        $total = $totalIncome - $totalExpense;
    
        return view('admin.expense.expense', compact('expenses','total'));
    }

    public function create()
    {
        abort_if(Gate::denies('expense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.expense.create');
    }

    public function store(StoreExpenseRequest $request)
    {
        $expense = Expense::create($request->all());
        $expense->user_id= auth()->user()->id;
        $expense->save();

        return redirect()->route('admin.expenses.index');
    }

    public function edit(Expense $expense)
    {
        abort_if(Gate::denies('expense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.expense.edit', compact('expense'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->all());
        $expense->user_id= auth()->user()->id;
        $expense->save();

        return redirect()->route('admin.expenses.index');
    }

    // public function show(Expense $expense)
    // {
    //     abort_if(Gate::denies('contact_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return view('admin.contacts.show', compact('contact'));
    // }

    public function destroy(Expense $expense)
    {
        abort_if(Gate::denies('expense_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense->delete();

        return back();
    }

    public function massDestroy(MassDestroyExpenseRequest $request)
    {
        $expenses = Expense::find(request('ids'));

        foreach ($expenses as $expense) {
            $expense->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('contact_create') && Gate::denies('contact_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Contact();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
