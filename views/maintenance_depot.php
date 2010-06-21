<h2>Ремонтная база</h2>
        <h5>кстати тут не работает ничего</h5>
        <form>
        <input name='service_type' id='service_type' type='hidden' value='' \>
        <select size='10' onchange="this.form.service_type.value=this.selectedIndex;" >
            <option value='0'>Покраска мультиустойчивым покрытием</option>
            <option value='1'>Рихтовка метеоритных отбойников</option>
            <option value='2'>Широкоспектральное тонирование иллюминаторов</option>

            <option value='3'>Замена маршевого двигателя</option>
            <option value='4'>Заправка</option>
        </select>
        <input type='button' value='купить' onclick="sendw('service','service_type');" \><br>
        </form>