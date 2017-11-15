import sys
import csv
import re
import pandas as pd

#import urllib.request
import requests


exportFileid = sys.argv[1]

pathToInputFile = sys.argv[2]

pathToExportFolder = sys.argv[3]

exportFilename = sys.argv[4]


url = sys.argv[5]


#Functions
##Trim file -- Takes in CSV File, returns array
def trimInput (filePath):
    # Opening Input File
    f = open(filePath)
    csv_f = csv.reader(f)

    # Temporay Store Variable for keeping rows
    temp = []

    for row in csv_f:
        # Catch all Keeping columns
        # Invoice ID
        invoiceID = row[0]
        product = row[1]
        invoiceDate = row[2]
        amount = row[3]
        email = row[16]
        client = row[24]
        billingAddress = row[25]
        billingAddressLineTwo = row[26]
        CardAddressCity = row[27]
        CardAddressState = row[28]
        CardAddressZip = row[30]

        rowItem = [invoiceID, product, invoiceDate, amount, email, client, billingAddress, billingAddressLineTwo,
                   CardAddressCity, CardAddressState, CardAddressZip]

        temp.append(rowItem)

    return temp

##Parce File
def parceNumOfHours(productDescription):
    #print(productDescription)
    num = re.findall(r'([\ ][0-9][\ ])+', productDescription)
    # Convert list to string and strip whitespace.
    num = ''.join(num)
    num = num.strip()
    return num


def parceArrayData(dataarray):
    parcedOutput = []
    #parcedOutput.append(dataarray[0])

    for row in dataarray[1:]:
        productPrice = float(row[3])
        nmlsFee = int(parceNumOfHours(row[1])) * 1.5
        adjustedPrice = productPrice - nmlsFee

        initRow = [row[0], row[1], row[2], str(adjustedPrice), row[4], row[5], row[6], row[7], row[8], row[9], row[10]]

        saleId = row[0]
        bankingFee = "NMLS Banking Fee"
        InvoiceDate = ''
        secondrowAmount = nmlsFee
        email = ''
        name = ''
        adrOne = ''
        adrTwo = ''
        cardCity = ''
        cardState = ''
        cardZip = ''

        bankingFeeRow = [saleId, bankingFee, InvoiceDate, secondrowAmount, email, name, adrOne, adrTwo, cardCity,
                         cardState, cardZip]

        parcedOutput.append(initRow)
        parcedOutput.append(bankingFeeRow)

    return parcedOutput

# Write to CSV -- Takes in array, returns CSV File

def outputToCSV(data, exportPath):
    df = pd.DataFrame(data, columns=["Invoice","Product/Service","Invoice Date","Amount","Email","Client","Billing Address","Billing Address Line 2","Billing Address Line 3","Billing Address Line 3","Billing Address Line 3"])
    df.to_csv(exportPath+'/'+exportFilename, index=False)


##Export file

# Trim data
trimedUserInput = trimInput(pathToInputFile)

# Parce data
parecedData = parceArrayData(trimedUserInput)

#Export file
outputToCSV(parecedData, pathToExportFolder)

#Send confirmation Get Request.

myurl = url+'/api/' + str(exportFileid) + '/' + exportFilename + '/'


r = requests.get(myurl)
#print(r)

#print(myurl)
#http://127.0.0.1:8000/api/44/payments_1.csv/
#a = urllib.request.urlopen(myurl)
