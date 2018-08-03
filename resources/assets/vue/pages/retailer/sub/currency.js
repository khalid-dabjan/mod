let  symbols = {
    "USD":'$',
    "EUR":'€',
    "GBP":'£',
    "IDR":'Rp'
} ;

let  arr =  [];

for( var symbol in symbols){
    arr.push(symbol)
}

export const currency = arr.filter((item, pos) => {
  return arr.indexOf(item) == pos;
});;


export const getCurrencySymbol =(currency)=>{
    return symbols[currency];
};

