/**
 * devuelte el primer lunes del mes-año
 * @param month - which month
 * @param year - which year
 * @returns {Date}
 */
function firstMonday(month, year) {
  let d = new Date(year, month, 1, 0, 0, 0, 0)
  let day = 0

  if (d.getDay() === 0) {
    day = 2
    d = d.setDate(day)
    d = new Date(d)
  } else if (d.getDay() !== 1) {
    day = 9 - (d.getDay())
    d = d.setDate(day)
    d = new Date(d)
  }

  return d
}

/**
 * devuelve el numero de das habiles entre dos fechas
 * @param d0
 * @param d1
 * @returns {number}
 */
function workingDaysBetweenDates(d0, d1) {
  //var holidays = ['2016-05-03','2016-05-05'];
  const startDate = parseDate(d0)
  const endDate = parseDate(d1)

  if (endDate < startDate) {
    return 0
  }

  const millisecondsPerDay = 86400 * 1000
  startDate.setHours(0, 0, 0, 1)
  endDate.setHours(23, 59, 59, 999)
  const diff = endDate - startDate
  let days = Math.ceil(diff / millisecondsPerDay)

  const weeks = Math.floor(days / 7)
  days -= weeks * 2

  const startDay = startDate.getDay()
  const endDay = endDate.getDay()

  if (startDay - endDay > 1) {
    days -= 2
  }
  if (startDay === 0 && endDay !== 6) {
    days--
  }
  if (endDay === 6 && startDay !== 0) {
    days--
  }
  /* Here is the code
  for (var i in holidays) {
      if ((holidays[i] >= d0) && (holidays[i] <= d1)) {
          days--;
      }
  }
  */
  return days
}

/**
 *
 * @param input
 * @returns {Date}
 */
function parseDate(input) {
  const parts = input.match(/(\d+)/g)
  return new Date(parts[0], parts[1] - 1, parts[2])
}

/**
 *
 * @param d0
 * @param d1
 * @returns {*}
 */
function daysBetweenDates(d0, d1) {
  const f_ini = moment(d0)
  const f_ter = moment(d1)

  return f_ter.diff(f_ini, 'days')
}

/**
 *
 * @param date
 * @returns {string}
 */
function getDateBD(date) {
  const aux = date.split('-')
  return aux[2] + '/' + aux[1] + '/' + aux[0]
}

/**
 *
 * @param date
 * @returns {string}
 */
function getDateToBD(date) {
  const aux = date.split('/')
  return aux[2] + '-' + aux[1] + '-' + aux[0]
}

/**
 *
 * @param date
 * @returns {string}
 */
function getDateHourBD(date) {
  const aux = date.split(' ')
  const aux2 = aux[0].split('-')
  return aux2[2] + '/' + aux2[1] + '/' + aux2[0] + ' ' + aux[1]
}

/**
 *
 * @param date
 * @returns {string}
 */
function getMonthDate(date) {
  const aux = date.split('-')
  const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']

  const month = months[aux[1] - 1]
  const year = aux[0]
  date = month + ' de ' + year
  return date
}

/**
 *
 * @returns {string}
 */
function getTodayDate() {
  const today = new Date()
  let dd = today.getDate()
  let mm = today.getMonth() + 1
  const yyyy = today.getFullYear()

  if (dd < 10) {
    dd = '0' + dd
  }

  if (mm < 10) {
    mm = '0' + mm
  }

  return dd + '/' + mm + '/' + yyyy
}

/**
 *
 * @param file
 * @returns {string}
 */
function getExt(file) {
  return file.split('.').pop().toLowerCase()
}

/**
 *
 * @param tx
 * @returns {string}
 */
function accentDecode(tx) {
  let rp = String(tx)
  rp = rp.replace(/&aacute;/g, 'á')
  rp = rp.replace(/&eacute;/g, 'é')
  rp = rp.replace(/&iacute;/g, 'í')
  rp = rp.replace(/&oacute;/g, 'ó')
  rp = rp.replace(/&uacute;/g, 'ú')
  rp = rp.replace(/&ntilde;/g, 'ñ')
  rp = rp.replace(/&uuml;/g, 'ü')
  rp = rp.replace(/&Aacute;/g, 'Á')
  rp = rp.replace(/&Eacute;/g, 'É')
  rp = rp.replace(/&Iacute;/g, 'Í')
  rp = rp.replace(/&Oacute;/g, 'Ó')
  rp = rp.replace(/&Uacute;/g, 'Ú')
  rp = rp.replace(/&Ñtilde;/g, 'Ñ')
  rp = rp.replace(/&Üuml;/g, 'Ü')
  rp = rp.replace(/&nbsp;/g, ' ')
  rp = rp.replace(/&quot;/g, '"')
  rp = rp.replace(/&ndash;/g, '-')
  rp = rp.replace(/&apos;/g, '\'')
  rp = rp.replace(/&lt;/g, '<')
  rp = rp.replace(/&gt;/g, '>')
  rp = rp.replace(/&amp;/g, '&')
  rp = rp.replace(/&euro;/g, '€')
  rp = rp.replace(/&iexcl;/g, '¡')
  rp = rp.replace(/&deg;/g, '°')
  return rp
}

/**
 *
 * @param number
 * @param decimals
 * @param dec_point
 * @param thousands_sep
 * @returns {string}
 */
function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '')

  const n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? ',' : dec_point
  let s = ''
  const toFixedFix = function (n, prec) {
    const k = Math.pow(10, prec)
    return '' + (Math.round(n * k) / k)
      .toFixed(prec)
  }

  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.')
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || ''
    s[1] += new Array(prec - s[1].length + 1)
      .join('0')
  }
  return s.join(dec)
}

/**
 * Custom libraries settings
 */
//CKEDITOR.config.customConfig = '../../bower_components/ckeditor/config.js';

$.fn.clearForm = function () {
  return this.each(function () {
    const type = this.type,
      tag = this.tagName.toLowerCase()
    if (tag === 'form') {
      return $(':input', this).clearForm()
    }
    if (type === 'text' || type === 'password' || tag === 'textarea' || tag === 'email') {
      this.value = ''
    } else if (type === 'checkbox' || type === 'radio') {
      this.checked = false
    } else if (tag === 'select') {
      this.selectedIndex = -1
    }
  })
}

$.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
  widgetPositioning: {
    horizontal: 'right',
    vertical: 'bottom'
  },
  locale: 'es'
})

$.extend($.fn.dataTable.defaults, {
	'dom': "<'row'<'col-md-4'B><'col-md-4 text-center'l><'col-md-4'f>>" + "<'row'<'col-md-12't>>" + "<'row'<'col-md-6'i><'col-md-6'p>>",
	'buttons': ['excel'],
	'paging': true,
	'lengthChange': true,
	'searching': true,
	'ordering': true,
	'info': true,
	'autoWidth': false,
	'language': {'url': 'dist/js/dataTable.spanish.json'},
	'order': [[0, 'desc']],
	'lengthMenu': [[20, 50, 100, -1], [20, 50, 100, 'Todo']],
	'pageLength': 20
});

Noty.overrideDefaults({
  theme: 'sunset',
  timeout: 3000,
  killer: true,
  closeWith: ['click']
})
