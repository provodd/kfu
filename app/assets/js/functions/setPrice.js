export default function setPrice(data){

    var price       = Number.prototype.toFixed.call(parseFloat(data) || 0, 2),
        //заменяем точку на запятую
        price_sep   = price.replace(/(\D)/g, ","),
        //добавляем пробел как разделитель в целых
        price_sep   = price_sep.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ");

    return price_sep;
}