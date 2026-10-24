
async function get_unidade(){
  try{
 const res = await fetch('../backend/get_unidades.php', {method: 'POST'});
 if(!res.ok){
throw new Error(`Erro HTTP: ${res.status}`);
}
let u = await res.json()
console.log(u)
 return u;
  }catch(error){
    console.log(error)
  }
}

async function generate_sel(){
  let unis = await get_unidade();
  let sel = document.createElement('select');
  sel.name ='unidade';


  unis.forEach(u=>{
    let op = document.createElement('option');
    op.value= `${u.unidade_id}`;
    op.textContent =`${u.nome}`
    sel.append(op);
})
return sel;
}

function show_sel(sel) {
let d = document.querySelector('#sels');
    d.append(sel);
}
generate_sel().then(show_sel)