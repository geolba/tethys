@extends('layouts.app')

@section('content')

    <h1 class="title">Datasets</h1>

    <br><br>

    <table class="pure-table pure-table-horizontal">

        <thead>
            <th>id</th>
            <th>dataset type</th>
            <!-- <th>Category</th>
            <th>Shelf</th> -->

        </thead>

        <tbody>
            
            @foreach($documents as $dataset)
            
                <tr>					
                    <td>                        
                        <a href="{{ route('frontend.dataset.show', ['id' => $dataset->id]) }}"> {{ $dataset->id }} </a> 
                    </td>
                    <td>{{ $dataset->type }}</td>
                    <!-- <td>
                        if($book->stock > 0)
                            Available
                        elseif($book->stock == 0)
                            -
                        endif
                    </td>				 -->	
                </tr>

            @endforeach	

        </tbody>
        
    </table>
        

    

@stop