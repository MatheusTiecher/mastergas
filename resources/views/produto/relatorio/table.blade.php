<table>
    <thead>
        <tr>
            <th>DESCRIÇÃO</th>
            <th>VENDA</th>
            <th>CUSTO MÉDIO</th>
            <th>MÍNIMO</th>
            <th>RESTANTE</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produtos as $produto)
        <tr>
            <td>{{$produto->pro_des}} {{$produto->uni_sig}}</td>
            <td>R$ {{number_format($produto->pro_ven, 2, ',', '.')}}</td>
            @if($produto->res != 0 || $produto->cus != 0)
            <td>R$ {{number_format($produto->cus / $produto->res, 2, ',', '.')}}</td>
            @else
            <td>R$ 0,0</td>
            @endif
            <td>{{number_format($produto->pro_min, 2, ',', '.')}} {{$produto->uni_sig}}</td>
            <td>{{number_format($produto->res, 2, ',', '.')}} {{$produto->uni_sig}}</td>
        </tr>
        @endforeach
    </tbody>
</table>