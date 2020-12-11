<!-- Mascaras para os campos -->
<script type="text/javascript" src="{{asset('assets/js/jquery.mask.min.js')}}"></script>
<script type="text/javascript">
        //setado como valor default tema do bootstrap
        $('.cpf').mask('000.000.000-00', {reverse: true});
        $('.cep').mask('00000-000', {reverse: true});
        $('.telefone').mask('(00) 0000-0000', {reverse: false});
        $('.celular').mask('(00) 00000-0000', {reverse: false});
        $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
        $('.money').mask('#.##0,00', {reverse: true});
        $('.date').mask('00/00/0000');
        $('.time').mask('00:00:00');
        $('.timef').mask('00:00');
    </script>