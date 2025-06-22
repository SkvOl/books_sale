
window.onload = () => {
    const request = async (data, url, func = undefined, method = 'GET') => {
        try {

            let queryString = '';
            let options =  {
                method: method,
                
                headers: {  
                    'Content-Type': 'application/json',  
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
                }
            };

            if(method == 'get' || method == 'GET'){
                queryString = new URLSearchParams(data).toString();  
                if( queryString != '') queryString = '?'+ queryString;
            }
            else options.body = JSON.stringify(data);
            

            let response = fetch(url + queryString, options);
    
            if(func !== undefined) response.then(res => { return res.json(); }).then(res => func(res));
            else return (await response).json();
    
        } catch (error) {
            console.error('Ошибка:', error);
        }
    }
    let sort_type = 'ASC', sort_item = 'id', current_page = 1, last_page = 1, entity = document.querySelector('.container').dataset.entity;
    

    const socketListener = (id, exeptions = []) => {
        window.Echo.channel(`EVENT_SALE_${id}`).listen('.EVENT_SALE', (data) => {
            let elem = document.querySelector(`.entry-${data.book.id}`);
            let row = '';

            for (const key in data.book) {
                if (data.book.hasOwnProperty(key) && !exeptions.includes(key)) {
                    row += `<td>${data.book[key]}</td>`;
                }
            }
            
            row += `<td><button type="button" class="btn btn-success sale" data-id="${id}">Купить</button></td>`;
            
            elem.innerHTML = row;
        });
        
        window.Echo.channel(`EVENT_BOOK_UPDATE_${id}`).listen('.EVENT_BOOK_UPDATE', (data) => {
            let elem = document.querySelector(`.entry-${data.book.id}`);
            let row = '';

            for (const key in data.book) {
                if (data.book.hasOwnProperty(key) && !exeptions.includes(key)) {
                    row += `<td>${data.book[key]}</td>`;
                }
            }
            
            row += `<td><button type="button" class="btn btn-success sale" data-id="${id}">Купить</button></td>`;

            elem.innerHTML = row;
        });
    };

    const load = async (table, data = {}, exeptions = []) => {
        request(data, `/api/${table}`, res=>{
            let rows = '<table class="table">';
            let header = '<thead><tr>';

            res.data.forEach((row, key_data) => {
                let id = row.id !== undefined ? row.id : 0, col = '';

                if(key_data == 0) {
                    for (const key in row) {
                        if (row.hasOwnProperty(key) && !exeptions.includes(key)) {
                            header += `<th>${key}</th>`;
                        }
                    }
                    rows += `${header}</tr></thead><tbody>`;
                }

                for (const key in row) {
                    if (row.hasOwnProperty(key) && !exeptions.includes(key)) {
                        if (row[key] === undefined) {
                            col = '';
                            return;
                        }

                        col += `<td>${row[key]}</td>`;
                    }
                }
                if(entity == 'books') col += `<td><button type="button" class="btn btn-success sale" data-id="${id}">Купить</button></td>`;

                if (col != '') rows += `<tr class = "entry-${id}">${col}</tr>`;

                socketListener(id, exeptions);
            });
    
            document.querySelector('.container').innerHTML = rows + '</tbody></table>';

            if(res.paginator.current_page !== undefined && res.paginator.current_page !== null){
                current_page = res.paginator.current_page;
                last_page = res.paginator.last_page;

                document.querySelector('.page-info').textContent = `Cтраница: ${current_page} из ${last_page}`;
            }
        });
    }

    const makeData = () => {
        let data = {
            sort_type: sort_type,
            sort_item: sort_item,
            page: current_page,
            search: document.querySelector('.search').value
        };

        let filters = document.querySelectorAll('.form-check-input.filter'), currentfilter;

        filters.forEach(filter => {
            if(filter.checked) {
                currentfilter = filter.dataset.filter;
                return;
            }
        });

        if(currentfilter == 'photo') data.photo = 1; 
        else if(currentfilter == 'count') data.sale_sort = 'DESC';
        else if(currentfilter == 'popular') data.popular = 1;

        return data;
    };

    


    load(entity, {}, ['authors']);



    document.querySelector('.search').addEventListener('input', e => {
        let elem = e.target;
        
        let data = makeData();

        data.search = elem.value;

        load(entity, data, ['authors']);
    })

    document.querySelector('.main-content').addEventListener('click', e => {
        let elem = e.target;

        if(elem.closest('th')){
            let data = makeData();
            sort_type = sort_type == 'DESC' ? 'ASC' : 'DESC';

            data.sort_item = elem.innerHTML;
            data.sort_type = sort_type;

            load(entity, data, ['authors']);
        }
        else if(elem.closest('.previous_page')){
            if(1 < current_page){
                let data = makeData();
    
                data.page = current_page - 1;

                load(entity, data, ['authors']);
            }
        }
        else if(elem.closest('.next_page')){
            if(current_page < last_page){
                let data = makeData();                
                
                data.page = current_page + 1;
                
                load(entity, data, ['authors']);
            }
        }
        else if(elem.closest('.filter')){
            let data = makeData();       
            
            load(entity, data, ['authors', 'sales_count']);
        }
        else if(elem.closest('.sale')){
            request({}, `/api/books/${elem.dataset.id}/buy`, res => {}, 'POST');
        }
    });
};