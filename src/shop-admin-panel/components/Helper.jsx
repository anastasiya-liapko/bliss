class Helper {
  static getLocation() {
    let location = `//${window.location.hostname}`;

    if (window.location.port) {
      location += `:${window.location.port}`;
    }

    return location;
  }

  static localizeNumber(value) {
    const number = parseFloat(value) || 0;
    return number.toLocaleString('ru-RU');
  }

  static localizeDate(value) {
    const date = new Date(value);
    return date.toLocaleString('ru-RU');
  }

  static getOrderStatusName(status) {
    let statusName;

    switch (status) {
      case 'waiting_for_registration':
        statusName = 'Ожидает заполнения кредитной заявки';
        break;
      case 'pending_by_mfi':
        statusName = 'На рассмотрении в ФО';
        break;
      case 'declined_by_mfi':
        statusName = 'Отклонён ФО';
        break;
      case 'canceled_by_client':
        statusName = 'Отклонён покупателем';
        break;
      case 'mfi_did_not_answer':
        statusName = 'МФО не успели ответить';
        break;
      case 'approved_by_mfi':
        statusName = 'Ожидает подтверждения покупателя';
        break;
      case 'pending_by_shop':
        statusName = 'Ожидает подтверждения магазина';
        break;
      case 'waiting_for_delivery':
        statusName = 'Ожидает доставки';
        break;
      case 'waiting_for_payment':
        statusName = 'Ожидает оплаты';
        break;
      case 'paid':
        statusName = 'Оплачен';
        break;
      case 'declined_by_shop':
        statusName = 'Отклонён магазином';
        break;
      case 'canceled_by_client_upon_receipt':
        statusName = 'Отменён покупателем при получении';
        break;
      default:
        statusName = '';
        break;
    }

    return statusName;
  }

  static addStatusName(item) {
    /**
     * @param item.request_id
     * @param item.request_status
     * @param item.order_id
     * @param item.order_price
     * @param item.goods
     * @param item.goods.name
     * @param item.goods.price
     * @param item.time_start
     * @param item.loan_id
     * @param item.loan_status
     * @param item.mfi_customer_id
     * @param item.mfi_contract_id
     * @param item.tracking_code
     * @param item.is_mfi_paid
     * @param item.customer_name
     * @param item.customer_phone
     * @param item.customer_additional_phone
     * @param item.mfi_name
     * @param item.delivery_service_name
     */
    const result = item;

    result.status_name = Helper.getOrderStatusName(item.status);

    return result;
  }

  static addStatusNameForArray(items) {
    return items.map(item => Helper.addStatusName(item));
  }

  static getFilterName(slug) {
    let filterName;

    switch (slug) {
      case 'order_id_in_shop':
        filterName = '№ заказа';
        break;
      case 'time_of_creation':
        filterName = 'Дата и время заказа';
        break;
      case 'status':
        filterName = 'Статус заказа';
        break;
      case 'tracking_code':
        filterName = 'Трек-номер';
        break;
      default:
        filterName = '';
        break;
    }

    return filterName;
  }

  static normalizeGoods(goods) {
    let result = '';

    for (let i = 0; i < goods.length; i += 1) {
      result += `${goods[i].name} — ${Helper.localizeNumber(goods[i].price)} руб. — ${goods[i].quantity} шт.;\n`;
    }

    return result.slice(0, -2);
  }
}

export default Helper;
