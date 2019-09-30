<?php

namespace App\Http\Controllers;


use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {

        $tasks = [];


        return view('index', compact('tasks'));
    }

    public function create()
    {
        return view('add');
    }

    public function store(Request $request)
    {


        //Khởi tạo mới đối tượng task, gán các trường tương ứng với request gửi lên từ trình duyệt
        $task = new Task();
        $task->title = $request->inputTitle;
        $task->content = $request->inputContent;
        $task->due_date = $request->inputDueDate;

        // Nếu file không tồn tại thì trường image gán bằng NULL
        if (!$request->hasFile('inputFile')) {
            $task->image = $request->inputFile;
        } else {
            $file = $request->file('inputFile');

            $fileExtension = $file->getClientOriginalExtension();

            $fileName = $request->inputFileName;
            // Gán tên mới cho file trước khi lưu lên server
            $newFileName = "$fileName.$fileExtension";
            //Lưu file vào thư mục storage/app/public/image với tên mới
            $request->file('inputFile')->storeAs('public/images', $newFileName);

            $task->image = $newFileName;
        }
        $task->save();
        return redirect()->route('tasks.index');
    }
}
