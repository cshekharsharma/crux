/**
 * Copyright (c) 2014 
 *
 * @author: shekhar
 * Date:  Mar 31, 2014
 */
package string;

import java.util.ArrayList;
import java.util.Collections;

/**
 *
 */
public class NumberStream {

    public static final int MAX_STREAM_LENGTH = 15;

    public int getNumberByIndex(int index) {
        int digitCount = 0;
        int numberAtLastIndex = 0;
        int tempIndex = index;
        while (tempIndex > 0) {
            tempIndex = tempIndex / 10;
            digitCount++;
        }

        for (int i = 0; i < digitCount; i++) {
            int lowerLimit = (int)Math.pow(10, i) - ((i == 0) ? 1 : 0);
            int upperLimit = 0;

            if (i < (digitCount - 1)) {
                upperLimit = ((int)Math.pow(10, i + 1) - 1);
            } else if (i == (digitCount - 1)) {
                upperLimit = index;
            }

            int availableIndices = upperLimit - lowerLimit + 1;
            
            int numbersInCurrentRange = ((lowerLimit - numberAtLastIndex) * i) (upperLimit - lowerLimit + 1) / (i + 1);
            int remainingFraction = (upperLimit - lowerLimit + 1) % (i + 1);
            numberAtLastIndex += numbersInCurrentRange;
            System.out.println("Lower: " + lowerLimit + "\t || Upper: " + upperLimit + "\t || NumberInRange: "
                    + numbersInCurrentRange + "\t || RemaingFraction: " + remainingFraction
                    + "\t || NumberAtLastIndex: " + numberAtLastIndex);
        }

        return 0;
    }

    public ArrayList<Integer> getSplitedNumber(int number) {
        ArrayList splitedNumber = new ArrayList<Integer>();
        while (number > 0) {
            splitedNumber.add(new Integer(number % 10));
            number = number / 10;
        }

        Collections.reverse(splitedNumber);
        return splitedNumber;
    }

    public static void main(String[] args) {

        NumberStream numberStream = new NumberStream();
        int[] stream = new int[MAX_STREAM_LENGTH];
        int i, j, streamIndex = 0;
        int searchKey = 1610;

        for (i = 0; streamIndex < MAX_STREAM_LENGTH; i++) {
            ArrayList<Integer> numberParts = numberStream.getSplitedNumber(i);
            for (j = 0; j < numberParts.size() && streamIndex < MAX_STREAM_LENGTH; j++) {
                stream[streamIndex] = numberParts.get(j);
                streamIndex++;
            }
        }

        System.out.println("Found: " + numberStream.getNumberByIndex(searchKey) + " at index:" + searchKey);
    }
}
